<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\ActivityLog;
use App\Models\Payout;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class USSDController extends Controller
{
    public function index(Request $request) {
        $sessionId   = $request->sessionId;
        $serviceCode = $request->serviceCode;
        $phoneNumber = $request->phoneNumber;
        $input       = $request->text;

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
        if($input_exploded[0] != "") {
            $this->run_app($level, $input_exploded, $phoneNumber);
        } else {
            /**
             * Login Phase of the app.
             */
            $content  = "Welcome to Belecom \n";
            $content .= "Shyiramo inimero y'umurasire wawe.  \n";
            $this->proceed($content);
        }
    }

    /**
     * proceed() is used to append CON on every USSD response message.
     * This informs the USSD API gateway that the USSD session is still in session and should still continue.
     */
    public function proceed($value){
        echo "CON $value";
    }

    /**
     * stop() is used to append END on every USSD response message.
     * This informs the USSD API gateway that the USSD session is terminated and should stop the app.
     */
    public function stop($value) {
        echo "END $value";
    }

    public function run_app($level, $values, $phoneNumber) {

        switch ($level) {

            /**
             * Menu Phase of the app.
             */
            case '1':
            /**
             * check if serial number entered Match any Record from user Accounts.
             */

            $check = $this->query_db('accounts', ['productNumber', $values[0]], ['isActive', 1]);
            if (!empty($check)) {
                $content  = "Ikaze kuri Belecom, ".$check->clientNames." \n";
                $content .= "Emeza: \n";
                $content .= "1 mwishyure ifatabuguzi ry'ukwezi. \n";
                $content .= "2 mwishyure ibirarane. \n";
                $content .= "3 Kubona ubutumwa bw'ibyakozwe. \n";
                $this->proceed($content);
            } else {

                /**
                 * ending session because this serial number doesn't exist
                 * or it hasn't assigned yet to anyone.
                **/

                $content  = "Nimero mwashyizemo ntiyandikishije! \n";
                $content .= "Gana ibiro bikwegereye bya Belecom bagufashe. \n";
                $content .= "Murakoze!";
                $this->stop($content);
            }

            break;

            /**
             * Payment Phase of the app
             */
            case '2':

                $check = $this->query_db('accounts', ['productNumber', $values[0]], ['isActive', 1]);

                if (!empty($check)) {

                    if($values[1] == "1") {
                        /**
                         * Check if entered Serial number exist in payout table.
                         *
                         */

                        $check_payout = $this->query_db('payouts', ['solarSerialNumber', $values[0]],NULL);

                        // if YES
                        if (!empty($check_payout)) {
                            $transactionID = sha1(md5(time())).'-'.rand(102,0);
                            $payment_fee = str_replace(',', '',$check_payout->payment);
                            $new_payout = new Payout();
                            $new_payout->solarSerialNumber = $values[0];
                            $new_payout->clientNames = $check_payout->clientNames;
                            $new_payout->clientID = $check_payout->clientID;
                            $new_payout->clientPhone = $phoneNumber;
                            $new_payout->monthYear = $check_payout->monthYear;
                            $new_payout->payment = $check_payout->payment;
                            $new_payout->transactionID = $transactionID;
                            $new_payout->status = 0;
                            $new = date('m-Y', strtotime(strtotime($check_payout->monthYear).' +2 month'));

                            /*
                             *                      to be done
                             * =========================================================
                             *      insert a new record with existing month + 1
                             * ---------------------------------------------------------
                             *  solarSerialNumber, clientNames, clientID
                             *  clientPhone, monthYear, payment, transactionID, status
                             *
                             *
                             * *********************************************************
                             */
                            $content  = "Mugiye kwishyura: <b>".$payment_fee." Rwf</b>! \n";
                            $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                            $content .= "Nyuma yo kwishyura murabona ubutumwa bugufi bwemeza ibyakozwe. \n";
                            $content .= "Murakoze!";
                            $this->payment_api($phoneNumber, $payment_fee, $transactionID);
                            $this->stop($content);
                        } else {
                        // if NO insert  new Record!

                            $transactionID = sha1(md5(time())).'-'.rand(102,0);
                            $new_payout = new Payout();
                            $new_payout->solarSerialNumber = $check->productNumber;
                            $new_payout->clientNames = $check->clientNames;
                            $new_payout->clientID = $check->beneficiary;
                            $new_payout->clientPhone = $phoneNumber;
                            $new_payout->monthYear = date("m-Y");
                            $new_payout->payment = $check->loan/36;
                            $new_payout->transactionID = $transactionID;
                            $new_payout->status = 0;
                            if($new_payout->save()){
                                $this->payment_api($phoneNumber,str_replace(',', '',number_format($check->loan/36)),$transactionID);
                                $this->ActivityLogs('Paying Loan','Beneficiary',$check->beneficiary);
                            } else {
                                $content  = "Ibyo musabye nibikunze mwogere mukanya \n Murakoze!.";
                                $this->stop($content);
                            }
                            $content = 'this'.$new_payout;
                            $this->proceed($content);
                        }

                    } else if($values[1] == "2") {
                        $content  = "Mufite ikirarane cya: <b>".number_format(100)."Rwf.</b>! \n";
                        $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                        $content .= "Murakoze!";
                        $this->stop($content);
                    } else if($values[1] == "3") {
                        $info = $this->query_db('payouts', ['solarSerialNumber', $values[0]],['status', 1]);
                        $content  = "Turaboherereza ubutumwa bugufi bukubiyemo incamake ku bwishyu bwose mwakoze. \n";
                        $content .= "Murakoze!";
                        $this->stop($content);
                        $message = $info->clientNames.' muheruka kwishura '.$info->payment.' kwitariki '.$info->created_at;
                        $this->BulkSms($phoneNumber,$message);
                    } else {
                        $content  = "Mwahisemo nabi! \n";
                        $content .= "Mwongere mugerageze nanone.";
                        $this->stop($content);
                    }
                 } else {
                    $content  = "inimero mwashizemo nago ibaho! \n Gana ibiro bikwegereye bya belecom bagufashe \n Murakoze!.";
                    $this->stop($content);
                 }
            break;

            /**
             * Default Phase of the app
             */
            default:
                $content = "Mwahisemo nabi! \n Mwongere mugerageze nanone.";
                $this->stop($content);
                break;
        }
    }

    public function query_db($model, $content, $constraint)
    {
        if($constraint == NULL){
            return DB::table($model)->where($content[0], $content[1])->first();
        }else{
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->first();
        }
    }

    public function payment_api($phone, $amount, $transactionID)
    {
        // $content = $phone.' '.$amount.' '.$transactionID;
        // $this->proceed($content);
            $url = "https://opay-api.oltranz.com/opay/paymentrequest";
            $content = '{
                "telephoneNumber" : "'.$phone.'",
                "amount" : "'.$amount.'",
                "organizationId" : "fa99567c-a2ab-4fbe-84c5-1d20093a3c8e",
                "description" : "Payment of Solar Panel",
                "callbackUrl" : "http://belecom.creators.rw/callback",
                "transactionId" : "'.$transactionID.'"
            }';
        // $url = "https://opay-api.oltranz.com/opay/paymentrequest";
        // $content = "{
        //     'telephoneNumber'   :   "''",
        //     'amount'            :   "",
        //     'organizationId'    :   'fa99567c-a2ab-4fbe-84c5-1d20093a3c8e',
        //     'description'       :   'Payment of Solar Panel',
        //     'callbackUrl'       :   '',
        //     'transactionId'     :   '""'
        // }";

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

    public function ActivityLogs($actionName,$modelName,$modelPrimaryKey)
    {
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

    // bulk sms
    public function BulkSms($number,$message){
        	$client = new Client([
    		'base_uri'=>'https://www.intouchsms.co.rw',
    		'timeout'=>'900.0'
    	]); //GuzzleHttp\Client

		// $result = $client->post('', [
		//     'form_params' => [
		//         'sample-form-data' => 'value'
		//     ]
		// ]);
		// $number = '0784101221';
		$result = $client->request('POST','api/sendsms/.json', [
		    'form_params' => [
		        'username' => 'Wilson',
		        'password' => '123Muhirwa',
		        'sender' => 'Belecom ltd',
		        'recipients' => $number,
		        'message' => $message,
		    ]
		]);
		// if ($result) {
		// 	return redirect()->back()->with('message','<script type="text/javascript">alert("message sent!!");</script>');
		// 	// return '';
             	


		// }
		// else{
		// 	return '<script type="text/javascript">alert("message not sent!!");</script>';
        //     return redirect()->back()->with('message',''); 	


		// }
		// return $result;
    }

    /*------------------- callBack ------------------*/
    public function callBackPayment_actual(Request $request){
        if($request->status === 'SUCCESS'){

            // Here you start querying in your DB,
            // $request->transactionId = Transaction you created in the DB, ugomba kuba wayi saving muri table 
            $transaction = DB::table('transactions')->where('transactionID', $request->transactionId)->first();
            $from = DB::table('temp_trans_info')->where('trans',$request->transactionId)
                    ->first();
            $user = DB::table('beneficiary')->where('identification',$from->b_id)->first();

            // Umaze kuyibona, you can update it to successful payed
            if(count($transaction)){

                # For Beneficially Account
                $data = array();
                $data['b_id']=$from->b_id;        
                $data['type_id']=$from->sp_type;
                $data['monthpaid']=$from->monthpaid;
                $data['monthleft']=$from->monthleft;
                $data['loan']=$from->loan;
                $data['updated_at']=Carbon::now();

                # For Payout
                $payout=array();
                $payout['type_id']=$from->sp_type;
                $payout['b_id']=$from->b_id;
                $payout['amountpaid']=$from->amountpaid;
                $payout['monthpaid']=$from->monthpaid;
                $payout['recorded_by']=$from->recorded_by;
                $payout['created_at']=Carbon::now();

                DB::table('beneficiary_account')
                ->where('b_id',$from->b_id)
                ->update($data);
                $getBen=DB::table('beneficiary_account')
                    ->join('beneficiary','beneficiary_account.b_id','beneficiary.identification')
                ->where('beneficiary_account.b_id',$from->b_id)
                ->first();
                $phone = $getBen->phone;
                $Accountid=$getBen->b_id;
                $activityLog=array();
                $activityLog['act_name']='paying installment ';
                $activityLog['doneBy']=$getBen->createdby;
                $activityLog['moreDetail']='on this date: '.$time.' you have  handled payment operation of user with ID:'.$Accountid;
                $activityLog['created_at']=Carbon::now();
                DB::table('activitylog')->insert($activityLog);

                DB::table('payout')->insert($payout);
                $payment = Transaction::find($transaction->id);
                $payment->status = "Payed";
                $payment->save();

                // Ino ni response usubiza abantu ba Opay kugira ubereke ko byaciyemo neza
                $myObj = new \stdClass();
                $myObj->message = 'Transaction succeeded!';
                $myObj->success = 'true';
                $myObj->request_id = $request->transactionId;
                $myJSON = json_encode($myObj);
                $client = new Client([
                'base_uri'=>'https://www.intouchsms.co.rw',
                'timeout'=>'900.0'
                ]); //GuzzleHttp\Client

                // $result = $client->post('', [
                //     'form_params' => [
                //         'sample-form-data' => 'value'
                //     ]
                // ]);
                $result = $client->request('POST','api/sendsms/.json', [
                    'form_params' => [
                        'username' => 'Wilson',
                        'password' => '123Muhirwa',
                        'sender' => 'Belecom ltd',
                        'recipients' => '0784101221',
                        'message' => $user->firstname.' '.$user->lastname.'Kwishura byagenze neza,  Murakoze Gukoresha Service yo kwishura ya belecom' 
                    ]
                    // 'recipients' => '0'.$user->phone,
                ]);
                return $myJSON;
            }
        } else {
            // Ino ni response usubiza abantu ba Opay kugira ubereke ko byakwamye tu
            $myObj = new \stdClass();
            $myObj->message = 'Transaction failed!';
            $myObj->success = 'true';
            $myObj->request_id = $request->transactionId;
            $myJSON = json_encode($myObj);
            $client = new Client([
            'base_uri'=>'https://www.intouchsms.co.rw',
            'timeout'=>'900.0'
            ]); //GuzzleHttp\Client

            // $result = $client->post('', [
            //     'form_params' => [
            //         'sample-form-data' => 'value'
            //     ]
            // ]);
            $result = $client->request('POST','api/sendsms/.json', [
                    'form_params' => [
                        'username' => 'Wilson',
                        'password' => '123Muhirwa',
                        'sender' => 'Belecom ltd',
                        'recipients' => '0784101221',
                        'message' => $user->firstname.' '.$user->lastname.'Kwishura Ntibikunze mwongere niba hari ikibazo baza umukozi wa belecom kugira agufashe,  Murakoze Gukoresha Service yo kwishura ya belecom' 
                    ]
                ]);
            return $myJSON;
        }
    }
}