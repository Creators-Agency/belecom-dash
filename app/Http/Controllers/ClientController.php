<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use SweetAlert;
use App\Models\Referee;
use App\Models\Account;
use App\Models\SolarPanel;
use App\Models\ActivityLog;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\SolarPanelType;
use App\Models\AdministrativeLocation;

class ClientController extends Controller
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
        $Get_clients = Beneficiary::where('isActive',1)
                        ->get();
        return view('client.add',[
            'clients' => $Get_clients
        ]);
    }
    
    public function actual()
    {
        $get_actual = Beneficiary::where('isActive',3)->get();
        return view('client.actual',[
            'clients' => $get_actual
        ]);
    }

    public function perspective()
    {
        $get_actual = Beneficiary::where('isActive',1)->get();
        return view('client.perspective',[
            'clients' => $get_actual
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveClient(Request $request)
    {
        // return $request;
        /*--------- Validating Data -------------------*/
        $rules = array (
            'firstName' => 'required',
            'lastName' => 'required',
            'identification' => 'required',
            'age' => 'required',
            'primaryNumber' => 'required',
            'location' => 'required',
            'villageName' => 'required',
            'quarterName' => 'required',
            'houseNumber' => 'required',
            'gender' => 'required',
            'refer' => 'required',
            'additional-info' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            alert()->error('Oops', 'Something Wrong!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
        /**
         * Check if user identification or phone exist in DB.
         */
        $check_user = Beneficiary::where('identification', $request->identification)
                                    ->orWhere('primaryPhone', $request->primaryNumber)
                                    ->count();
        if ($check_user == 0) {
            
            $client = new Beneficiary();
            $client->firstname = $request->firstName;
            $client->lastname =$request->lastName;
            $client->identification =$request->identification;
            $client->gender =$request->gender;
            $client->DOB =$request->age;
            $client->primaryPhone = $request->primaryNumber;
            $client->secondaryPhone =$request->secondaryNumber;
            $client->educationLevel =$request->education;
            $client->incomeSource =$request->sourceOfIncome;
            $client->sourceOfEnergy =$request->sourceOfEnergy;
            $client->location =$request->location;
            $client->village =$request->villageName;
            $client->quarterName =$request->quarterName;
            $client->houseNumber =$request->houseNumber;
            $client->buildingMaterial =$request->roofMaterial;
            $client->familyMember =$request->numberOfPeopleSchool;
            $client->membersInSchool =$request->memberInSchool;
            $client->U18Male =$request->majorM;
            $client->U17Male =$request->minorM;
            $client->U18Female =$request->majorF;
            $client->U17Female =$request->minorF;
            $client->employmentStatus = 0;
            $client->referredby = $request->referredby;
            $client->isActive = 1;
            $client->doneBy = 1;
            

            // if he/she heard about from someone
            if ($request->refer = 1) {
                $refer = new Referee();
                $refer->refereeName = $request->names;
                $refer->refereeID = $request->identityReferee;
                $refer->referrePhone = $request->refereeNumber;
                $refer->relationship = $request->relationship;
                /*----check if inserted*/
                if($refer->save()){
                    $get_refer = Referee::orderBy('id','DESC')->first();
                    $client->referredby = $get_refer->id;

                    #save client
                    if ($client->save()) {
                        
                        /*============== Updating Activity Logs =========*/
                        $Get_beneficiary = Beneficiary::orderBy('id','DESC')->first();
                        $this->ActivityLogs('Registration','Beneficiary', $Get_beneficiary->id);
                        alert()->success('yes','done');
                        return Redirect('/client');
                    }
                    alert()->error('alert','dumb');
                    return Redirect::back()->withErrors($validator)->withInput();
                }
                alert()->warning('Oops','Error occured');
                return Redirect::back()->withErrors($validator)->withInput();

            }
            else{
                $client->referredby = 0;

                #save client
                if ($client->save()) {
                    
                    /*============== Updating Activity Logs =========*/
                    $Get_beneficiary = Beneficiary::orderBy('id','DESC')->first();
                    $this->ActivityLogs('Registration','Beneficiary', $Get_beneficiary->id);
                    alert()->success('yes','done');
                    return Redirect('/client');
                }            

            }
        }else{
            /**
             * redirect back to the previous page with inputs!.
             */
            alert()->error('User exist, try with different identification','Operation Failed!');
            return Redirect::back()->withErrors($validator)->withInput();
        }

    }

    /**
     * Assign solar panel, View
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign($id)
    {
        $client = Beneficiary::where('id',$id)->first();
        $solarType = SolarPanelType::get();
        return view('client.assign',[
            'client' => $client,
            'SolarTypes' => $solarType
        ]);
    }

    /**
     * Show the form for assigning client a solar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignClient(Request $request)
    {
        /*------------getting solar using type selected----------------*/
        $serialNumber = SolarPanel::where('solarPanelType',$request->solarPanelType)
                                ->where('status',0)
                                ->first();
        $account = new Account();
        $account->beneficiary = $request->clientIdentification;
        $account->productNumber = $serialNumber->solarPanelSerialNumber;
        $account->clientNames = $request->firstname;
        $account->loan = $request->price;
        $account->doneBy = 1;

        /*------- check if this number does not exist in account table*/
        $chec_account = Account::where('beneficiary', $request->clientIdentification)
                                ->orWhere('productNumber', $serialNumber->solarPanelSerialNumber)
                                ->count();
        if ($chec_account == 0) {
            
            /*---------------- save and check if succed ------------*/
            if ($account->save()) {

                /* changing Beneficiary status*/
                 Beneficiary::where('identification', $request->clientIdentification)
                    ->update([
                        'isActive' => 3
                    ]);

                /* changing solar status*/
                 solarPanel::where('solarPanelSerialNumber', $serialNumber->solarPanelSerialNumber)
                    ->update([
                        'status' => 1
                    ]);
                /*----------updating activity log--------------*/
                $Get_account = Account::orderBy('id','DESC')->first();
                $this->ActivityLogs('Creating new','Account',$Get_account->id);

                alert()->success('yes','done');
                return Redirect('/client');

            }else{
                /*----------Catch error if it failed to save------------*/
                alert()->error('Something went Wrong!','Oops!!');
                return Redirect()->back()->withErrors($validator)->withInput();
            }
        }else{
            /*-------------Catch error if account exist-----------------*/
            alert()->warning('Account exist!','Oops!!');
            return Redirect('/client');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateClient(Request $request, $id)
    {
        //
    }


    /**
     *
     * Blade for editing client
     *
     * @return View
     * @param int $id of the client
     *
     */

    public function editClient($id, $dob)
    {
        $Get_clients = Beneficiary::where('isActive',1)
                                    ->where('id', $id)
                                    ->get();
        return view('client.edit', [
            '$clients' => $Get_clients
        ]);
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