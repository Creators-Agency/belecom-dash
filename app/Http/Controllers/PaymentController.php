<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use SweetAlert;
use App\Models\Charge;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\SolarPanelType;

/*
 *                          Payment
 * =========================================================
 *                      CRUD Operations 
 * ---------------------------------------------------------
 *  Model:Referee, Account, SolarPanel, ActivityLog, 
 *  Beneficiary, Payout, SolarPanelType, 
 *  AdministrativeLocation
 * ---------------------------------------------------------
 *  Addtional info:
 * *********************************************************
 */

class PaymentController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment.payment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function charge()
    {
        $Get_solarType = SolarPanelType::orderBy('id','DESC')
                        ->where('isActive',1)
                        ->get();

        $Get_solarType = Charge::orderBy('id','DESC')
                        ->get(); 
        $charges =  SolarPanelType::get();  
        // return $charges->charge;          
        return view('payment.charge',[
            'SolarTypes' => $Get_solarType,
            'ifRecord' => count($Get_solarType)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveCharges(Request $request)
    {

        /**
         *          New charge
         * ---------------------------------
         * Constraints: only one Record with 
         * it's charge is allowed 
         * no duplicate is allowed
         * 
         */

        /*--------- Validating Data -------------------*/
        $rules = array (
            'solarPanelType' => 'required',
            'charges' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $check = Charge::where('solarPanelType', $request->solarPanelType)->first();
        // check if exist
        if (empty($check)) {
            $charge = new Charge();
            $charge->solarPanelType = $request->solarPanelType;
            $charge->charges = $request->charges;
            $charge->doneBy =  Auth::User()->id;
            if ($charge->save()) {

                /*============== Updating Activity Logs =========*/
                $Get_charge = Charge::orderBy('id','DESC')->first();
                $this->ActivityLogs('New','Charge', $Get_charge->id);
                alert()->warning('New charge added', 'Scuccess')->autoclose(3500);
                return Redirect('/payment');
            }
        }
        else{
            alert()->warning('Charge Of This Solar Type is already set! you can edit', 'Oops!')->autoclose(3500);
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            $activityLog->userID = 1; //Authenticated user
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

}