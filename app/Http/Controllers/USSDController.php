<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Payout;

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
        /**
         * check if serial number entered Match any Record from user Accounts.
         */

        $check = $this->query_db('accounts', ['productNumber', $values[0]], ['isActive', 1]);
        switch ($level) {

            /**
             * Menu Phase of the app.
             */

            case '1':
                if (!empty($check)) {
                    $content  = "Ikaze kuri Belecom, ".$check->clientName." \n";
                    $content .= "Emeza: \n";
                    $content .= "1 mwishyure ifatabuguzi ry'ukwezi. \n";
                    $content .= "2 mwishyure ibirarane. \n";
                    $content .= "3 Kubona ubutumwa bw'ibyakozwe. \n";
                    $this->proceed($content);
                }
                else{

                    /**
                     * ending session because this serial number doesn't exist 
                     * or it hasn't assigned yet to anyone.
                     */

                    $content  = "inimero mwashizemo nago ibaho! \n Gana ibiro bikwegereye bya belecom bagufashe \n Murakoze!.".$values[1];
                    $this->stop($content);
                // $this->query_db('payouts', ['id', '9090909']);
                // $this->payment_api('0784101221', '100');

                
            }

            break;

            /**
             * Payment Phase of the app
             */
            case '2':
                if($values[1] == "1") {
                    /**
                     * Check if entered Serial number exist in payout table. 
                     * 
                     */

                    $check_payout = $this->query_db('payouts', ['solarSerialNumber', $values[0]],NULL);

                    // if YES
                    if (!empty($check_payout)) {
                        $content  = "Mukwiye kwishyura: <b>".number_format(100)."Rwf.</b>! \n";
                        $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                        $content .= "Murakoze!";
                        $this->stop($content);
                    }else{
                    // if NO insert  new Record!


clientNames
monthPaid
loan
monthleft
                        $new_payout = new Payout();
                        $new_payout->solarSerialNumber = $check->productNumber;
                        $new_payout->clientNames = $check->clientNames;
                        $new_payout->clientID = $check->beneficiary;
                        $new_payout->clientPhone = $check->$values[1]
                        $new_payout->monthYear = $check->
                        $new_payout->payment = $check->
                        $content = 'this'.$check->clientNames;
                        $this->proceed($content);
                    }
                    
                } else if($values[1] == "2") {
                    $content  = "Mufite ikirarane cya: <b>".number_format(100)."Rwf.</b>! \n";
                    $content .= "Mwemeze ubwishyu mukoresheje Airtel Money / MTN MoMo. \n";
                    $content .= "Murakoze!";
                    $this->stop($content);
                } else if($values[1] == "3") {
                    $content  = "Turaboherereza ubutumwa bugufi bukubiyemo incamake ku bwishyu bwose mwakoze. \n";
                    $content .= "Murakoze!";
                    $this->stop($content);
                } else {
                    $content  = "Mwahisemo nabi! \n Mwongere mugerageze nanone.";
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

    public function payment_api($phone, $amount)
    {
        $transactionID = sha1(md5(time())).'-'.rand(102,0);
        $url = "https://opay-api.oltranz.com/opay/paymentrequest";
        $content = "{
            'telephoneNumber'   :   '".$phone."',
            'amount'            :   ".$amount.",
            'organizationId'    :   'fa99567c-a2ab-4fbe-84c5-1d20093a3c8e',
            'description'       :   'Payment of Solar Panel',
            'callbackUrl'       :   'http://belecom.creators.rw/callback',
            'transactionId'     :   '".$transactionID."'
        }";

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
}