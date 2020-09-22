<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

class USSDController extends Controller
{
    /**
     * Set default level to zero
     */
    public $level = 0;

    /* Split text input based on asteriks(*)
    * USSD API gateway usually appends asteriks for after every menu level or input made.
    * One needs to split the response in order to determine the menu level and input for each level.
     */
    // public $input_exploded = explode("*", $input);

    /**
     * Get menu level from client's input
     */
    // public $level = count($input_exploded);
    /**
     * Check level of application and display content accordingly
     */
    
    public function index(Request $request)
    {
        // $sessionId   = $_POST["sessionId"];
        $sessionId   = $request->sessionId;
        // $serviceCode = $_POST["serviceCode"];
        $serviceCode = $request->serviceCode;
        // $phoneNumber = $_POST["phoneNumber"];
        $phoneNumber = $request->phoneNumber;
        // $input       = $_POST["text"];
        $input       = $request->text;
         $level = 0;
         $input_exploded = explode("*", $input);

         $level = count($input_exploded);

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
    function proceed($value){
        header('Content-type: text/plain');
        echo "CON $value";
    }

    /**
     * stop() is used to append END on every USSD response message.
     * This informs the USSD API gateway that the USSD session is terminated and should stop the app.
     */
    function stop($value) {
        header('Content-type: text/plain');
        echo "END $value";
    }

    /**
     * display_menu() is the main menu of our application.
     */
    function display_menu() {
        $content  = "Welcome to Belecom \n";
        $content .= "Enter your Serial Number to pay bill.  \n";
        $this->proceed($content);
    }

    function run_app($value) {
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
                    stop($content);
                } else if($value[2] == "2"){
                    $content = "Enter amount you wish to pay:";
                    $this->proceed($content);
                } else {
                    stop("Invalid Choice! \n Please try again later.");
                }
                break;
            case '4':
                if($value[3] > 7000){
                    stop("Amount entered is greated than balance! \n Please try again later.");
                } else {
                    $content  = "Proceed to pay on you MoMo account! \n";
                    $content .= "Amount: <b>".number_format($value[3])." Rwf.</b>\n";
                    $content .= "Thank you!";
                    stop($content);
                }
                
                break;
            default:
                stop("Thank you for using Belecom!");
                break;
        }
    }
}
