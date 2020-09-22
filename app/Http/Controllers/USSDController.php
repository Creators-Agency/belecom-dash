<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

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
        $level = count($input_exploded);

        /**
         * Check level of application and display content accordingly
         */

        if($level <= 1){
            $this->display_menu();
        } else if($level > 1) {
            $this->run_app($input_exploded);
        }
    }
    /**
     * proceed() is used to append CON on every USSD response message.
     * This informs the USSD API gateway that the USSD session is still in session and should still continue.
     */
    public function proceed($value){
        header('Content-type: text/plain');
        echo "CON $value";
    }

    /**
     * stop() is used to append END on every USSD response message.
     * This informs the USSD API gateway that the USSD session is terminated and should stop the app.
     */
    public function stop($value) {
        header('Content-type: text/plain');
        echo "END $value";
    }

    /**
     * display_menu() is the main menu of our application.
     */
    public function display_menu() {
        $content  = "Welcome to Belecom \n";
        $content .= "Enter your Serial Number to pay bill.  \n";
        $this->proceed($content);
    }

    public function run_app($value) {
        $level = count($value);
        switch ($level) {
            case '2':
                $content  = "Valentin, Tubahaye ikaze kuri Belecom \n";
                $content .= "Kanda";
                $content .= "1 wishure ifatabuguzi y'ukwezi \n";
                $content .= "2 wishure amezi wihitiyemo\n";
                $content .= "3 wishure ibirarane \n";
                $content .= "4 kureba ibyakozwe \n";
                $this->proceed($content);
                break;

            case '3':
                if($value[2] == "1") {
                    $content  = "Proceed to pay on you MoMo account! \n";
                    $content .= "Amount: <b>".number_format(7000)."Rwf.</b>\n";
                    $content .= "Thank you!";
                    $this->stop($content);
                } else if($value[2] == "2"){
                    $content = "Enter amount you wish to pay:";
                    $this->proceed($content);
                } else {
                    $this->stop("Invalid Choice! \n Please try again later.");
                }
                break;
            case '4':
                if($value[3] > 7000){
                    $this->stop("Amount entered is greated than balance! \n Please try again later.");
                } else {
                    $content  = "Proceed to pay on you MoMo account! \n";
                    $content .= "Amount: <b>".number_format($value[3])." Rwf.</b>\n";
                    $content .= "Thank you!";
                    $this->stop($content);
                }

                break;
            default:
                $this->stop("Thank you for using Belecom!");
                break;
        }
    }
}