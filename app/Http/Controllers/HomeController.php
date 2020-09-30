<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Charts\Analytics;

/*models*/
use App\Models\Beneficiary;
use App\Models\SolarPanel;
use App\Models\Payout;
use App\Models\Account;


class HomeController extends Controller {
    public function home() {

    	/*
		 *                          Dashboard
		 * =========================================================
		 *                      CRUD Operations 
		 * ---------------------------------------------------------
		 *  Model:Referee, Account, SolarPanel, ActivityLog, 
		 *  Beneficiary, Payout, SolarPanelType, 
		 *  AdministrativeLocation
		 * ---------------------------------------------------------
		 *  Addtional info: will display analytics
		 * *********************************************************
		 */

    	/*
		 * getting client, perspective
    	 */
    	$get_perspective = Beneficiary::where('isActive', 1)->get();

    	/*
		 * getting client, actual
    	 */

    	$get_actual = Beneficiary::where('isActive', 3)->get();

    	/*
		 * getting latest client
    	 */
    	
    	// geting last week date from now
    	$latestDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d 20:30:00').' -7 day'));

    	$get_latest_client = Beneficiary::where('isActive', 1)
								->where('created_at','>=', $latestDate)
    							->get();
    	$get_latest_actual = Beneficiary::where('isActive', 3)
								->where('created_at','>=', $latestDate)
								->get();
    	/*
		 * getting latest sales
    	 */

    	$get_latest_sales = Payout::where('status', 1)
								->where('created_at','>=', $latestDate)
    							->sum('payment');

    	/*
		 * getting loan amount of all clients
    	 */

    	$get_loan = Account::where('isActive', 1)
    							->sum('loan');

    	/*
		 * getting number of registered panel
    	 */

    	$get_solar = SolarPanel::where('status', 1)
    							->get();
        $analytics = new Analytics;
        $analytics->labels(['Jan', 'Feb', 'Mar']);
        $analytics->dataset('Users by trimester', 'line', [10, 25, 13]);

        return view('dashboard', [
        	// Analytics Graph
            'analytics' => $analytics,

            // perspective clients data
            'perspectives' => count($get_perspective),
            'latestPerspectives' => count($get_latest_client),

            // actual clients data
            'actuals' => count($get_actual),
            'latestActualClient' => count($get_latest_actual),

            // sales and loans about clients data
            'loanAmount' => number_format($get_loan),
            'latestSalesAmount' => number_format($get_latest_sales),

            // get registered panel
            'panels' => number_format(count($get_solar))

        ]);
    }
}