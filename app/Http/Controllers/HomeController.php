<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Charts\Analytics;
use DB;

/*models*/
use App\Models\AdministrativeLocation;
use App\Models\Beneficiary;
use App\Models\SolarPanel;
use App\Models\Payout;
use App\Models\Account;


class HomeController extends Controller {
	public function __construct() {
        $this->middleware('auth');
    }
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
    	/*
		 * getting location
    	 */

    	$get_location = AdministrativeLocation::where('status', 1)
    							->get();

    	/*infographics about all sold solar */
    	$data_loan = DB::table('accounts')
            ->select(DB::raw('sum(accounts.loan) as `amount`'), DB::raw('MONTHNAME(accounts.created_at) month'))
            ->groupby('month')
            ->orderBy('created_at', 'ASC')
            ->where('isActive',1)
            ->get(); 

        $month_loan = [];
        $amount_loan = [];

        foreach ($data_loan as $point) {
            array_push($amount_loan, $point->amount);
            array_push($month_loan, $point->month);
        }

        $loans = new Analytics;
        $loans->labels($month_loan);
        $loans->dataset('Amount of sold panel', 'line', $amount_loan);



        /*info about monthly payment*/
        $data_payment = DB::table('payouts')
                ->select(DB::raw('sum(payment) as `amount`'), DB::raw('MONTHNAME(created_at) month'))
                ->groupby('month')
                ->orderBy('created_at', 'ASC')
                ->where('status',1)
                ->get();
        $month_payment = [];
        $amount_payment = [];

        foreach ($data_payment as $point) {
            array_push($amount_payment, $point->amount);
            array_push($month_payment, $point->month);
        } 
        $payments = new Analytics;
        $payments->labels($month_payment);
        $payments->dataset('Loan Paid in Frw', 'line', $amount_payment);

        return view('dashboard', [
        	// Analytics Graph
            'loans' => $loans,
            'payments' => $payments,

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
            'panels' => number_format(count($get_solar)),
            // locations
            'locations' => number_format(count($get_location))

        ]);
	}
	
}