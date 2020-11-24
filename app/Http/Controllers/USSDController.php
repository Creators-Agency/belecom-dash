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
        // $sessionId   = $request->sessionId;
        // $serviceCode = $request->serviceCode;
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
            $content  = "Tubahaye ikaze kuri Belecom \n";
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

            $check = $this->query_db('accounts', ['productNumber', $values[0]], ['isActive', 1], NULL, NULL);
            if (!empty($check)) {
                $content  = "Ikaze kuri Belecom, ".$check->clientNames." \n";
                // $content = $check;
                $content .= "Emeza: \n";
                $content .= "1 mwishyure ifatabuguzi ry'ukwezi. \n";
                // $content .= "2 mwishyure ibirarane. \n";
                $content .= "2 Kubona ubutumwa bw'ibyakozwe. \n";
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

                $check = $this->query_db('accounts', ['productNumber', $values[0]], ['isActive', 1], NULL, NULL);

                if (!empty($check)) {

                    if($values[1] == "1") {
                        /**
                         * Check if entered Serial number exist in payout table.
                         *
                         */

                        $check_payout = $this->query_db('payouts', ['solarSerialNumber', $values[0]], ['status', '1'], NULL, ['id','DESC']);
                        if(!empty($check_payout) && $check_payout->balance  <1){
                            $content  = " Nta deni mufite \n Murakoze!.";
                            $this->stop($content);
                        break;
                        }
                       
                        if (!empty($check_payout)) {
                            $transactionID = sha1(md5(time())).'-'.rand(102,0);
                            $payment_fee = $check_payout->payment;
                            $new = date('m-Y', strtotime(strtotime($check_payout->monthYear).' +1 month'));
                            $new_payout = new Payout();
                            $new_payout->solarSerialNumber = $values[0];
                            $new_payout->clientNames = $check_payout->clientNames;
                            $new_payout->clientID = $check_payout->clientID;
                            $new_payout->clientPhone = $phoneNumber;
                            $new_payout->monthYear = $new;
                            
                            $new_payout->transactionID = $transactionID;
                            $new_payout->status = 0;
                            

                            // check if amount left in balance are payable or add them on last payment

                            if((($check_payout->balance - $payment_fee) < $payment_fee) && ($check_payout->balance - $payment_fee)>0){
                                $payment_fee = $payment_fee + ($check_payout->balance - $payment_fee);
                                $new_payout->payment = $payment_fee;
                                $new_payout->balance = 0;
                            }else {
                                $new_payout->payment = $check_payout->payment;
                                $new_payout->balance = $check_payout->balance - $payment_fee;
                            }
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
                            if($new_payout->save()){
                                $this->payment_api($phoneNumber, $payment_fee, $transactionID);
                                $this->ActivityLogs('Paying Loan','Solarpanel',$check->productNumber);
                            } else {
                                $content  = "Ibyo musabye nibikunze mwogere mukanya \n Murakoze!.";
                                $this->stop($content);
                            }
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
                            $new_payout->balance = $check->loan - ($check->loan/36);
                            $pay = round($check->loan/36, 0);
                            if($new_payout->save()){
                                $this->payment_api($phoneNumber,$check->loan/36,$transactionID);
                                $this->ActivityLogs('Paying Loan','Solarpanel',$check->productNumber);
                            } else {
                                $content  = "Ibyo musabye nibikunze mwogere mukanya \n Murakoze!.";
                                $this->stop($content);
                            }
                            $content  = "Mugiye kwishyura: <b>".$pay." Rwf</b>! \n";
                            $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                            $content .= "Nyuma yo kwishyura murabona ubutumwa bugufi bwemeza ibyakozwe. \n";
                            $content .= "Murakoze!";
                            $this->proceed($content);
                        }
                    }
                    //  else if($values[1] == "2") {
                    //     $content  = "Mufite ikirarane cya: <b>".number_format(100)."Rwf.</b>! \n";
                    //     $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                    //     $content .= "Murakoze!";
                    //     $this->stop($content);
                    // } 
                    else if($values[1] == "2") {
                        $info = $this->query_db('payouts', ['solarSerialNumber', $values[0]],['status', 0], NULL, ['id', 'DESC']);
                        $content  = "Turaboherereza ubutumwa bugufi bukubiyemo incamake ku bwishyu bwose mwakoze. \n";
                        $content .= $info->clientNames.' muheruka kwishura '.$info->payment.' kwitariki '.$info->created_at;
                        $content .= "\n Murakoze!";
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

    public function query_db($model, $content, $constraint, $constraint2, $order)
    {
        if($constraint == NULL && $constraint2 ==NULL && $order ==NULL){
            return DB::table($model)->where($content[0], $content[1])->first();
        // for sort as main 
        }elseif ($constraint == NULL && $constraint2 == NULL && $order != NULL) {
            return DB::table($model)->where($content[0], $content[1])->orderBy($order[0],$order[1])->first();
            // return 'order not null';
        } elseif ($constraint == NULL && $constraint2 != NULL && $order != NULL) {
            // return 'order and constraint 2 not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint2[0], $constraint2[1])->orderBy($order[0],$order[1])->first();
        }elseif ($constraint == NULL && $constraint2 != NULL && $order == NULL) {
            // return 'constraint 2 not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint2[0],$constraint2[1])->first();
        }elseif ($constraint != NULL && $constraint2 == NULL && $order != NULL) {
            // return 'order and constraint 1 not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->orderBy($order[0],$order[1])->first();
        }elseif ($constraint != NULL && $constraint2 == NULL && $order == NULL) {
            // return 'constraint not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0],$constraint[1])->first();
        }elseif ($constraint != NULL && $constraint2 != NULL && $order == NULL) {
            // return 'constraint 1 and constraint 2 not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0],$constraint[1])->where($constraint2[0],$constraint2[1])->first();
        }else{
            // return 'not null';
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->where($constraint2[0], $constraint2[1])->orderBy($order[0],$order[1])->first();
        }
    }
    public function query_db_sum($model, $content, $constraint)
    {
        if($constraint == NULL){
            return DB::table($model)->where($content[0], $content[1])->sum('payment');
        }else{
            return DB::table($model)->where($content[0], $content[1])->where($constraint[0], $constraint[1])->sum('payment');
        }
    }

    public function payment_api($phone, $amount, $transactionID)
    {
            $url = "https://opay-api.oltranz.com/opay/paymentrequest";
            $content = '{
                "telephoneNumber" : "'.$phone.'",
                "amount" : "'.$amount.'",
                "organizationId" : "fa99567c-a2ab-4fbe-84c5-1d20093a3c8e",
                "description" : "Payment of Solar Panel",
                "callbackUrl" : "http://197.243.14.87/ussd/callBack",
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

        /*
            Method to send bulk sms
            
        */
    public function BulkSms($number,$message){
        	$client = new Client([
    		'base_uri'=>'https://www.intouchsms.co.rw',
    		'timeout'=>'900.0'
    	]); //GuzzleHttp\Client

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

    public function paymentCallBack(Request $request)
    {
        /* if transaction is successfuly */
        if($request->status === 'SUCCESS'){
            try {
                $get = Payout::where('transactionID', $request->transactionId)->first();
                if(isset($get) == 1){
                    $update = Payout::where('transactionID', $request->transactionId)
                    ->update([
                        'status' => 1
                    ]);
                    $myObj = new \stdClass();
                    $myObj->message = 'Transaction succeeded!';
                    $myObj->success = 'true';
                    $myObj->request_id = $request->transactionId;
                    $myJSON = json_encode($myObj);
                    $com = Beneficiary::where('identification', $get->clientID)->first();
                    $phone = '0'.$com->primaryPhone;
                    $message = $com->firstname." turakumenyesha ko igikorwa cyo kwishura cyagenze neza \n Murakoze!";
                    $this->BulkSms($phone,$message);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            $get = Payout::where('transactionID', $request->transactionId)->first();
            $com = Beneficiary::where('identification', $get->clientID)->first();
            $phone = '0'.$com->primaryPhone;
            $message = 'Mukiriya mwiza kwishura ntibyagenze neza!';
            $this->BulkSms($phone,$message);
        }
        return true;
    }
}