<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\ActivityLog;
use App\Models\Payout;
use App\Models\Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Stmt\Break_;

class USSDController extends Controller {
    /**
     * proceed() is used to append CON on every USSD response message.
     * This informs the USSD API gateway that the USSD session is still in session and should still continue.
     */
    public function proceed($value, $sessionId) {
        header('Freeflow: FC');
        header('cpRefId: '.$sessionId);
        echo $value;
    }

    /**
     * stop() is used to append END on every USSD response message.
     * This informs the USSD API gateway that the USSD session is terminated and should stop the app.
     */
    public function stop($value, $sessionId) {
        header('Freeflow: FB');
        header('cpRefId: '.$sessionId);
        echo $value;
    }

    public function welcome(Request $request) {
        $input          = $request->get('input');
        $number         = $request->get('phoneNumber');
        $sessionId      = $request->get('sessionId');
        $newRequest     = $request->get('newrequest');
        // trim country code
        $msisdn = substr($number, 2);
        if(!$newRequest) {
            $session = Session::where("sessionId", $sessionId)->orderBy("created_at", "DESC")->first();
            $input = $session->input."*".$input;
        }

        $session = new Session();
        $session->msisdn = $msisdn;
        $session->sessionId = $sessionId;
        $session->input = $input;
        $session->newRequest = $newRequest;
        $session->save();

        $this->index($input, $msisdn, $sessionId);
    }

    public function index($input, $msisdn, $sessionId) {
        /**
        * Set default level to zero
        */
        $level = 0;

        /* Split text input based on asteriks(*)
        * USSD API gateway usually appends asteriks for after every menu level or input made.
        * One needs to split the response in order to determine the menu level and input for each level.
        */
        $input_exploded = explode("*", $input);

        /**
         * Get menu level from client's input
         */
        $level = sizeof($input_exploded);

        /**
         * Fire up the app passing the level of application and content from input
         */
        if($level <= 1) {
            /**
             * Login Phase of the app.
             */
            $content  = "Ikaze kuri Belecom.\n";
            $content .= "Shyiramo inimero y'umurasire wawe.\n";
            $this->proceed($content, $sessionId);
        } else {
            $this->run_app($level, $input_exploded, $msisdn, $sessionId);
        }
    }

    public function run_app($level, $values, $phoneNumber, $sessionId) {
        switch ($level) {
            /**
             * Menu Phase of the app.
             */
            case '2':
                /**
                 * user can't pay if solar is under maintenance
                 * check solar status
                 */
                $solarStatus = $this->query_db('solar_panels',['solarPanelSerialNumber',$values[1]],NULL,NULL,NULL);
                if ($solarStatus->status == 3) {
                    $content  = "Ntago mushobora kwishura\n";
                    $content .= "Umurasire wanyu uracyari gukorwa.\n";
                    $content .= "Murakoze!";
                    $this->stop($content, $sessionId);
                }
                /**
                 * check if serial number entered Match any Record from user Accounts.
                 */
                $check = $this->query_db('accounts', ['productNumber', $values[1]], ['isActive', 1], NULL, NULL);
                if (!empty($check)) {
                    $content  = "Ikaze kuri Belecom, ".$check->clientNames."\n";
                    $content .= "Emeza:\n";
                    $content .= "1: Kwishyura ifatabuguzi ry'ukwezi.\n";
                    $content .= "2: Kwishyura ibirarane\n";
                    $content .= "3: Kubona ubutumwa bw'ibyakozwe.\n";
                    $this->proceed($content, $sessionId);
                } else {
                    /**
                     * Ending session because this serial number doesn't exist
                     * or it hasn't assigned yet to anyone.
                    **/
                    $content  = "Nimero mushyizemo ntibaruye.\n";
                    $content .= "Gana ibiro bikwegereye bya Belecom bagufashe.\n";
                    $content .= "Murakoze!";
                    $this->stop($content, $sessionId);
                }
                break;

            /**
             * Payment Phase of the app
             */
            case '3':
                /**
                 * check if serial number entered Match any Record from user Accounts.
                 */
                $check = $this->query_db('accounts', ['productNumber', $values[1]], ['isActive', 1], NULL, NULL);
                if (!empty($check)) {
                    if($values[2] == "1") {
                        // /**
                        //  * Check if entered Serial number exist in payout table.
                        //  */
                        // $check_payout = $this->query_db('payouts', ['solarSerialNumber', $values[1]], ['status', '1'], ['accountStatus', '0'], ['id','DESC']);
                        // /**
                        //  * check if clients has no amount due
                        //  */
                        // if(!empty($check_payout) && $check_payout->balance  < 1) {
                        //     $content  = "Nta deni mufite\n";
                        //     $content .= "Murakoze!";
                        //     $this->stop($content, $sessionId);
                        // }
                        /**
                         * ask client to input amount of money
                         */
                        $content= $check->clientNames."  Shyiramo umubare wamafaranga";
                        $this->proceed($content, $sessionId);
                        
                    }elseif ($values[2] == "2") {
                        $content  = "iyi service ntirigukora wongere ugerageze nyuma yamasaha 24\n";
                        $content .= "Murakoze!";
                        $this->stop($content, $sessionId);
                    }else if($values[2] == "3") {
                        $info = $this->query_db('payouts', ['solarSerialNumber', $values[1]],['status', 0], NULL, ['id', 'DESC']);

                        $message = $info->clientNames.' muheruka kwishura '.$info->payment.' kwitariki '.$info->created_at;
                        $this->BulkSms($phoneNumber,$message);

                        $content  = "Turaboherereza ubutumwa bugufi bukubiyemo incamake ku bwishyu bwose mwakoze.\n";
                        $content .= "Murakoze!";
                        $this->stop($content, $sessionId);
                    } else {
                        $content  = "Mwahisemo nabi!\n";
                        $content .= "Mwongere mugerageze nanone.";
                        $this->stop($content, $sessionId);
                    }
                } else {
                    /**
                     * Ending session because this serial number doesn't exist
                     * or it hasn't assigned yet to anyone.
                    **/
                    $content  = "Nimero mushyizemo ntibaho.\n";
                    $content .= "Gana ibiro bikwegereye bya Belecom bagufashe.\n";
                    $content .= "Murakoze!";
                    $this->stop($content, $sessionId);
                }
                break;
            case '4':
                
                $check = $this->query_db('accounts', ['productNumber', $values[1]], ['isActive', 1], NULL, NULL);
                /**
                 * Check if entered Serial number exist in payout table.
                 */
                $check_payout = $this->query_db('payouts', ['solarSerialNumber', $values[1]], ['status', '1'], ['accountStatus', '0'], ['id','DESC']);
                
                /**
                 * check if clients has no amount due
                 */
                if(!empty($check_payout) && $check_payout->balance  < 1) {
                    $content  = "Nta deni mufite\n";
                    $content .= "Murakoze!";
                    $this->stop($content, $sessionId);
                }
                if (!empty($values[3])) {
                    if (!empty($check_payout)) {
                        // $this->stop($values[3], $sessionId);
                        $transactionID = sha1(md5(time())).rand(102,0);
                        /**
                         * check if recent transaction has paid the expected
                         * monthly amount
                         */
                        if ($check_payout->payment < ($check->loan/$check->loanPeriod)) {
                            $payment_fee = $values[3] + $check_payout->payment;
                        }else{
                            $payment_fee = $values[3];
                        }

                        /**
                         * check if amount inputed is not much more than client's balance
                         */

                        if ($check_payout->balance < $payment_fee) {
                            $content  = "Mushizemo amafaranga menshi kurenza ayo mugomba \n";
                            $content  = "Kwishura ariyo ".$check_payout->balance."\n";
                            $content .= "Murakoze!";
                            $this->stop($content, $sessionId);
                        break;
                        }

                        /**
                         * calculate and loop to create installment to paid 
                         * -- all transaction which made at the same time should share the same transaction ID 
                         * 
                         * 
                         * if amount entered is greater than monthly amount 
                         * variable called period will increase 1 which means one month
                         * and the rest of money will be created as new payment for next month
                         * 
                         */
                        $new = 0;
                        $period = 0;
                        while($payment_fee>0){
                            $new_payout = new Payout();
                            $period++;
                            $new = date('m-Y', strtotime(strtotime($check_payout->monthYear).' +'.$period.' month'));
                            $new_payout->solarSerialNumber = $values[1];
                            $new_payout->clientNames = $check_payout->clientNames;
                            $new_payout->clientID = $check_payout->clientID;
                            $new_payout->clientPhone = $phoneNumber;
                            $new_payout->monthYear = $new;
                            $new_payout->transactionID = $transactionID;
                            $new_payout->status = 0;
                            /**
                             * check if payment fees is greater than monthly 
                             *  -if yes we will keep inserting expected monthly amount
                             *  -else we will keep the fee amount it hold 
                             */
                            if ($payment_fee >($check->loan/$check->loanPeriod)) {
                                $new_payout->payment = $check->loan/$check->loanPeriod;
                            }else{
                                $new_payout->payment =$payment_fee;
                            }
                            /**
                             * removing expected monthly amount from inputed amount
                             */
                            $payment_fee = $payment_fee - $check->loan/$check->loanPeriod;
                            $new_payout->balance = $check_payout->balance - $check->loan/$check->loanPeriod;

                            // /**
                            //  * Check if amount left in balance are payable or add them on last payment.
                            //  */
                            // if((($check_payout->balance - $payment_fee) < $payment_fee) && ($check_payout->balance - $payment_fee)>0) {
                            //     $payment_fee = $payment_fee + ($check_payout->balance - $payment_fee);
                            //     $new_payout->payment = $payment_fee;
                            //     $new_payout->balance = 0;
                            // } else {
                            //     $new_payout->payment = $check_payout->payment;
                            //     $new_payout->balance = $check_payout->balance - $payment_fee;
                            // }

                            /**
                             * TODO:
                             * =========================================================
                             * Insert a new record with existing month + 1
                             * solarSerialNumber, clientNames, clientID
                             * clientPhone, monthYear, payment, transactionID, status
                             * =========================================================
                             */
                            if($new_payout->save()) {
                                $this->ActivityLogs('Paying Loan','Solarpanel',$check->productNumber);
                            } else {
                                Payout::where('transactionID', $transactionID)->update(['status' => 10]);
                                $content  = "Ibyo musabye ntibikunze.\n";
                                $content .= "Mwihangane mwogere mukanya.\n";
                                $content .= "Murakoze!";
                                $this->stop($content, $sessionId);
                            }
                            break;
                        }
                        $content  = "Mugiye kwishyura: ".$values[3]." Rwf.\n";
                        $content .= "Kanda *182*7# Mwemeze ubwishyu mukoresheje MTN MoMo.\n";
                        $content .= "Murakoze!";
                        $this->payment_api($phoneNumber, $values[3], $transactionID);
                        $this->stop($content, $sessionId);
                    } else {
                        /**
                         * Insert New Record if we can't find any payment.
                         */
                        $transactionID = sha1(md5(time())).rand(102,0);
                        if($values[3] >($check->loan/$check->loanPeriod)){
                            $payment_fee = $values[3];
                            $new = 0;
                            $period = 0;
                            while($payment_fee>0){
                                $new_payout = new Payout();
                                $period++;
                                $new = date('m-Y'.' +'.$period.' month');
                                $new_payout->solarSerialNumber = $check->productNumber;
                                $new_payout->clientNames = $check->clientNames;
                                $new_payout->clientID = $check->beneficiary;
                                $new_payout->clientPhone = $phoneNumber;
                                $new_payout->monthYear = $new;
                                $new_payout->transactionID = $transactionID;
                                $new_payout->status = 0;
                                /**
                                 * check if payment fees is greater than monthly 
                                 *  -if yes we will keep inserting expected monthly amount
                                 *  -else we will keep the fee amount it hold 
                                 */
                                if ($payment_fee >($check->loan/$check->loanPeriod)) {
                                    $new_payout->payment = $check->loan/$check->loanPeriod;
                                }else{
                                    $new_payout->payment =$payment_fee;
                                }
                                $payment_fee = $payment_fee - $check->loan/$check->loanPeriod;
                                $new_payout->balance = $check_payout->balance - $check->loan/$check->loanPeriod;
                                if($new_payout->save()) {
                                    $this->ActivityLogs('Paying Loan','Solarpanel',$check->productNumber);
                                } else {
                                    Payout::where('transactionID', $transactionID)->update(['status' => 10]);
                                    $content  = "Ibyo musabye ntibikunze.\n";
                                    $content .= "Mwihangane mwogere mukanya.\n";
                                    $content .= "Murakoze!";
                                    $this->stop($content, $sessionId);
                                }
                                break;
                            }
                            $content  = "Mugiye kwishyura: ".$values[3]." Rwf.\n";
                            $content .= "Kanda *182*7# Mwemeze ubwishyu mukoresheje MTN MoMo.\n";
                            $content .= "Murakoze!";
                            $this->payment_api($phoneNumber, $values[3], $transactionID);
                            $this->stop($content, $sessionId);
                        }
                        $new_payout = new Payout();
                        $new_payout->solarSerialNumber = $check->productNumber;
                        $new_payout->clientNames = $check->clientNames;
                        $new_payout->clientID = $check->beneficiary;
                        $new_payout->clientPhone = $phoneNumber;
                        $new_payout->monthYear = date("m-Y");
                        $new_payout->payment = $check->loan/$check->loanPeriod;
                        $new_payout->transactionID = $transactionID;
                        $new_payout->status = 0;
                        $new_payout->balance = $check->loan - ($check->loan/$check->loanPeriod);
                        $pay = round($check->loan/$check->loanPeriod, 0);
                        if($new_payout->save()) {
                            $this->payment_api($phoneNumber,$check->loan/$check->loanPeriod,$transactionID);
                            $this->ActivityLogs('Paying Loan','Solarpanel',$check->productNumber);
                        } else {
                            $content  = "Ibyo musabye ntibikunze.\n";
                            $content .= "Mwihangane mwogere mukanya.\n";
                            $content .= "Murakoze!";
                            $this->stop($content, $sessionId);
                        }
                        $content  = "Mugiye kwishyura: ".$pay." Rwf.\n";
                        $content .= "kanda *187*1# Mwemeze ubwishyu\n";
                        $content .= "Murakoze!";
                        $this->stop($content, $sessionId);
                    }

                }
                else{
                    $content  = "Amafaranga ntagomba kuba ubusa\n";
                    $content .= "Murakoze!";
                    $this->stop($content, $sessionId);
                }

                break;

            /**
             * Default Phase of the app
             */
            default:
                $content  = "Mwahisemo nabi!\n";
                $content .= "Mwongere mugerageze nanone.";
                $this->stop($content, $sessionId);
                break;
        }
    }

    public function query_db($model, $content, $constraint, $constraint2, $order) {
        if($constraint == NULL && $constraint2 ==NULL && $order ==NULL) {
            return DB::table($model)->where($content[0], $content[1])->first();
        } elseif ($constraint == NULL && $constraint2 == NULL && $order != NULL) {
            return DB::table($model)->where($content[0], $content[1])->orderBy($order[0],$order[1])->first();
        } elseif ($constraint == NULL && $constraint2 != NULL && $order != NULL) {
            return DB::table($model)->where($content[0], $content[1])->where($constraint2[0], $constraint2[1])->orderBy($order[0],$order[1])->first();
        } elseif ($constraint == NULL && $constraint2 != NULL && $order == NULL) {
            return DB::table($model)->where($content[0], $content[1])->where($constraint2[0],$constraint2[1])->first();
        } elseif ($constraint != NULL && $constraint2 == NULL && $order != NULL) {
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->orderBy($order[0],$order[1])->first();
        } elseif ($constraint != NULL && $constraint2 == NULL && $order == NULL) {
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0],$constraint[1])->first();
        } elseif ($constraint != NULL && $constraint2 != NULL && $order == NULL) {
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0],$constraint[1])->where($constraint2[0],$constraint2[1])->first();
        } else {
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->where($constraint2[0], $constraint2[1])->orderBy($order[0],$order[1])->first();
        }
    }

    public function query_db_sum($model, $content, $constraint) {
        if($constraint == NULL) {
            return DB::table($model)->where($content[0], $content[1])->sum('payment');
        } else {
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->sum('payment');
        }
    }

    public function payment_api($phone, $amount, $transactionID) {
            $url = "https://opay-api.oltranz.com/opay/paymentrequest";
            $content = '{
                "telephoneNumber" : "'.$phone.'",
                "amount" : "'.$amount.'",
                "organizationId" : "fa99567c-a2ab-4fbe-84c5-1d20093a3c8e",
                "description" : "Payment of Solar Panel",
                "callbackUrl" : "http://197.243.14.87/ussd/callback",
                "transactionId" : "'.$transactionID.'"
            }';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function ActivityLogs($actionName,$modelName,$modelPrimaryKey) {
        /*
            Sometimes it might failed to save, but we gotta give it a try!
            this method will repeat atleast 1 time if it fail,
            else it will break to loop
        */
        for ($i=0; $i <1 ; $i++) {

            $activityLog = new ActivityLog();
            $activityLog->userID = 0; //Authenticated user
            $activityLog->actionName = $actionName;
            $activityLog->modelName = $modelName;
            $activityLog->modelPrimaryKey = $modelPrimaryKey;

            // if sucess
            if ($activityLog->save()) {
                // break the loop
                break;
            }
        }
    }

    /*
        Method to send bulk sms
    */
    public function BulkSms($number,$message) {
        // GuzzleHttp\Client
        $client = new Client([
            'base_uri'=>'https://www.intouchsms.co.rw',
            'timeout'=>'900.0'
        ]);

		$result = $client->request('POST','api/sendsms/.json', [
		    'form_params' => [
		        'username' => 'Wilson',
		        'password' => '123Muhirwa',
		        'sender' => 'Belecom ltd',
		        'recipients' => $number,
		        'message' => $message,
		    ]
		]);
    }

    /*------------------- callBack ------------------*/

    public function paymentCallBack(Request $request) {
        /* if transaction is successfuly */
        if($request->status == "SUCCESS") {
            $get = Payout::where('transactionID', $request->transactionId)->first();
            if(isset($get)) {
                Payout::where('transactionID', $request->transactionId)->update(['status' => 1]);
                $myObj = new \stdClass();
                $myObj->message = 'Transaction succeeded!';
                $myObj->success = 'true';
                $myObj->request_id = $request->transactionId;
                $myJSON = json_encode($myObj);
                $com = Beneficiary::where('identification', $get->clientID)->first();
                $phone = '0'.$com->primaryPhone;
                $message = $com->firstname." turakumenyesha ko igikorwa cyo kwishura cyagenze neza \n umubare uranga ubwishu'.$request->transactionId.\n Murakoze!";
                $this->BulkSms($phone, $message);
            }
        } else {
            /**
             * message 
             */
            $get = Payout::where('transactionID', $request->transactionId)->first();
            if(isset($get)) {
                $com = Beneficiary::where('identification', $get->clientID)->first();
                $phone = '0'.$com->primaryPhone;
                $message = 'Mukiriya mwiza kwishura ntibyagenze neza!';
                $this->BulkSms($phone,$message);
            }
        }

        return true;
    }
}