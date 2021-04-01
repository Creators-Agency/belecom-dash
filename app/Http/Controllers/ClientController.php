<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use SweetAlert;
use Auth;
use DB;
use App\Models\Referee;
use App\Models\Account;
use App\Models\SolarPanel;
use App\Models\ActivityLog;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\SolarPanelType;
use App\Models\AdministrativeLocation;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
        $get_location = AdministrativeLocation::where('status', 1)
                        ->get();
        if(count($get_location) == 0){
            alert()->warning('You should first add Operational area','Operational Area not found')->autoclose(4500);
            return redirect('stock/new/location');
        }
        $Get_clients = DB::table('beneficiaries')
                        ->join('administrative_locations','administrative_locations.id','=','beneficiaries.location')
                        ->select(
                            'beneficiaries.id as clientID',
                            'beneficiaries.identification',
                            'beneficiaries.firstname',
                            'beneficiaries.lastname',
                            'beneficiaries.gender',
                            'beneficiaries.DOB',
                            'beneficiaries.primaryPhone',
                            'administrative_locations.id as locationID',
                            'administrative_locations.locationName',
                            )
                        ->where('beneficiaries.isActive',1)
                        ->where('administrative_locations.status',1)
                        ->get();
        return view('client.add',[
            'clients' => $Get_clients,
            'locations' => $get_location,
            'pageTitle' =>'Add clients'
        ]);
    }

    public function actual()
    {
        $get_actual = DB::table('beneficiaries')
                        ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                        ->where('beneficiaries.isActive',3)
                        ->get();
        return view('client.actual',[
            'clients' => $get_actual,
            'pageTitle' =>'Actuals clients'

        ]);
    }

    public function perspective()
    {
        $Get_clients = DB::table('beneficiaries')
                        ->join('administrative_locations','administrative_locations.id','=','beneficiaries.location')
                        ->select(
                            'beneficiaries.id as clientID',
                            'beneficiaries.identification',
                            'beneficiaries.firstname',
                            'beneficiaries.lastname',
                            'beneficiaries.gender',
                            'beneficiaries.DOB',
                            'beneficiaries.primaryPhone',
                            'administrative_locations.id as locationID',
                            'administrative_locations.locationName',
                            )
                        ->where('beneficiaries.isActive',1)
                        ->where('administrative_locations.status',1)
                        ->get();
        return view('client.perspective',[
            'clients' => $Get_clients,
            'pageTitle' =>'Perspective clients'

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
            // 'age' => 'required',
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
            $client->primaryPhone = '0'.$request->primaryNumber;
            $client->secondaryPhone = '0'.$request->secondaryNumber;
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
            // $client->referredby = $request->referredby;
            $client->isActive = 1;
            $client->doneBy =  Auth::User()->id;


            // if he/she heard about from someone
            if ($request->refer == 1) {
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
                        alert()->success('Operation has been successful','User Has been Registered!');
                        return Redirect('/client');
                    }
                    alert()->error('Something Goes Wrong Try again if issues persist contact system admin','Oops')->autoclose(5500);
                    return Redirect::back()->withErrors($validator)->withInput();
                }
                alert()->warning('Something went wrong during operation!','Oops');
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
            alert()->error('Identification Exist in our database, use different!','User exist!');
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
        $solarType = SolarPanelType::get();
        if(count($solarType) == 0){
            alert()->warning('No Solar Panel type available','Alert')->autoclose(4500);;
            return redirect('stock/new/solar/type');
        }
        $solarType = SolarPanelType::get();
        $solar = SolarPanel::where('status', 0)->get();
        $btn = NULL;
        if(count($solar) == 0){
            $btn = 1;
            // alert()->warning('No Solar Panel type available','Alert')->autoclose(4500);;
            // return redirect('stock/new/solar/type');
        }
        $client = Beneficiary::where('id',$id)->first();

        return view('client.assign',[
            'client' => $client,
            'SolarTypes' => $solarType,
            'btn' => $btn,
            'pageTitle' =>'Assign Client clients'
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
        // return $request;
        if($request->price == 0){
            alert()->success('this session has expired retry again!','Oops');
            return Redirect('/client/perspective');
        }
        if($request->loansPeriod >'36' ){
            alert()->success('Time period should not exceed 36 months','Oops');
            return Redirect('/client/perspective');
        }
        if($request->loansPeriod < 1){
            alert()->success('Minimum time Period is one month!','Oops');
            return Redirect('/client/perspective');
        }
        /*------------getting solar using type selected----------------*/
        $serialNumber = SolarPanel::where('solarPanelType',$request->solarPanelType)
                                ->where('status',0)
                                ->first();
        $account = new Account();
        $account->loanPeriod = $request->loansPeriod;
        $account->beneficiary = $request->clientIdentification;
        $account->productNumber = $serialNumber->solarPanelSerialNumber;
        $account->clientNames = $request->firstname;
        if ($request->loansPeriod != 36) {
            // calculate amount paid if he/she already paying with old-fashioned way
            $monthPaid = 36 - $request->loansPeriod;
            // $amountPaid = ($request->price * $monthPaid)/36;
            $amountLeft = $request->price - $request->paidPrice;
        } else {
            $amountLeft = $request->price;
        }
        $account->loan = $amountLeft;
        $account->doneBy =  Auth::User()->id;

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
                $getClients = Beneficiary::where('identification', $request->clientIdentification)->first();

                /* changing solar status*/
                 solarPanel::where('solarPanelSerialNumber', $serialNumber->solarPanelSerialNumber)
                    ->update([
                        'status' => 1,
                        'moreInfo' => 0
                    ]);

                if ($request->loansPeriod != 36) {
                    // insert month he already paid using old fashioned way
                    // for($i = 1; $i<=$monthPaid; $i++){
                        $transactionID = sha1(md5(time())).'-'.rand(102,0);
                        $new_payout = new Payout();
                        $new_payout->solarSerialNumber = $serialNumber->solarPanelSerialNumber;
                        $new_payout->clientNames = $request->firstname;
                        $new_payout->clientID = $request->clientIdentification;
                        $new_payout->clientPhone = $getClients->primaryPhone;
                        $new_payout->monthYear = date("m-Y");
                        $new_payout->payment = $request->paidPrice;
                        $new_payout->transactionID = $transactionID;
                        $new_payout->status = 1;
                        $new_payout->balance = round($request->price - $request->paidPrice,0);
                        $new_payout->save();
                    // }
                }
                /*---------- sending message -----*/
                    // get user
                    $user = Beneficiary::where('identification', $request->clientIdentification)->first();
                    $phoneNu ='0'.$user->primaryPhone;
                    $message = $user->lastname.' Tuguhaye Ikaze mubafatabuguzi ba Belecom, inomero iranga umurasire ni:'.$serialNumber->solarPanelSerialNumber.', Kanda *652*'.$serialNumber->solarPanelSerialNumber.'# wishure, Murakoze!';
                $this->sendBulk($phoneNu,$message);
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
            return 'Account exist! Oops!!';
            alert()->warning('Account exist!','Oops!!');
            return Redirect('/client');
        }
    }



    /**
     *
     * Blade for editing client
     *
     * @return View
     * @param int $id of the client
     *
     */

    public function editClient($id, $identification)
    {
        $Get_clients = Beneficiary::where('identification', $id)
                                    ->first();
        if (empty($Get_clients)) {
            alert()->warning('Encounted error with this record!','Oops!!');
            return Redirect::back();
        }
        return view('client.edit', [
            'client' => $Get_clients
        ]);
    }

    public function updateClient(Request $request)
    {
        // return $request;
        if ($request->refer == 1) {
        /**
         * check if referee existing in our db
         */
        $request->identityReferee;
        $check = Referee::where('refereeID',$request->identityReferee)->first();
        if (!Empty($check)) {
            $refer = $check->id;
        }
            $refer = new Referee();
            $refer->refereeName = $request->names;
            $refer->refereeID = $request->identityReferee;
            $refer->referrePhone = $request->refereeNumber;
            $refer->relationship = $request->relationship;
            if($refer->save()){
                $get_refer = Referee::orderBy('id','DESC')->first();
                $refer = $get_refer->id;
            }

        }
        else {
            $refer = 0;
        }
        try {
            Beneficiary::where('id', $request->id)
                ->update([
                    'firstname'=>$request->firstName,
                    'lastname'=>$request->lastName,
                    'identification'=>$request->identification,
                    'gender'=>$request->gender,
                    'DOB'=>$request->age,
                    'primaryPhone'=>$request->primaryNumber,
                    'secondaryPhone'=>$request->secondaryNumber,
                    'educationLevel'=>$request->education,
                    'incomeSource'=>$request,
                    'sourceOfEnergy'=>$request,
                    'location'=>$request->location,
                    'village'=>$request->villageName,
                    'quarterName'=>$request->quarterName,
                    'houseNumber'=>$request->houseNumber,
                    'buildingMaterial'=>$request->roofMaterial,
                    'familyMember'=>$request->numberOfPeopleSchool,
                    'membersInSchool'=>$request->memberInSchool,
                    'U18Male' =>$request->majorM,
                    'U17Male' =>$request->minorM,
                    'U18Female' =>$request->majorF,
                    'U17Female' =>$request->minorF,
                    'employmentStatus'=>$request->employmentStatus,
                    'referredby'=>$refer,
                ]);
            $this->ActivityLogs('editing customer', 'Beneficiary',$request->id);
            alert()->success('User has been updated','Success!');
            return Redirect::back();
        } catch (\Throwable $th) {
            alert()->error('System countered errors during operation, try or contact system admin!','Oops something wrong!');
            return Redirect::back()->withInput(); 
        }

    }


    public function viewClient($id, $identification)
    {

        $userData_perspective = DB::table('beneficiaries')
                    ->select(
                        'beneficiaries.id as clientID',
                        'beneficiaries.firstname',
                        'beneficiaries.lastname',
                        'beneficiaries.identification',
                        'beneficiaries.gender',
                        'beneficiaries.DOB',
                        'beneficiaries.primaryPhone',
                        'beneficiaries.secondaryPhone',
                        'beneficiaries.educationLevel',
                        'beneficiaries.incomeSource',
                        'beneficiaries.sourceOfEnergy',
                        'beneficiaries.location',
                        'beneficiaries.village',
                        'beneficiaries.quarterName',
                        'beneficiaries.houseNumber',
                        'beneficiaries.buildingMaterial',
                        'beneficiaries.familyMember',
                        'beneficiaries.membersInSchool',
                        'beneficiaries.U18Male',
                        'beneficiaries.U17Male',
                        'beneficiaries.U18Female',
                        'beneficiaries.U17Female',
                        'beneficiaries.employmentStatus',
                        'beneficiaries.referredby',
                        'beneficiaries.isActive',
                        'beneficiaries.created_at',
                    )
                    ->where('identification',$id)
                    ->first();
        
        $userData_actual = DB::table('beneficiaries')
                    ->join('payouts','payouts.clientID','beneficiaries.identification')
                    ->select(
                        'payouts.balance',
                        'beneficiaries.id as clientID',
                        'beneficiaries.firstname',
                        'beneficiaries.lastname',
                        'beneficiaries.identification',
                        'beneficiaries.gender',
                        'beneficiaries.DOB',
                        'beneficiaries.primaryPhone',
                        'beneficiaries.secondaryPhone',
                        'beneficiaries.educationLevel',
                        'beneficiaries.incomeSource',
                        'beneficiaries.sourceOfEnergy',
                        'beneficiaries.location',
                        'beneficiaries.village',
                        'beneficiaries.quarterName',
                        'beneficiaries.houseNumber',
                        'beneficiaries.buildingMaterial',
                        'beneficiaries.familyMember',
                        'beneficiaries.membersInSchool',
                        'beneficiaries.U18Male',
                        'beneficiaries.U17Male',
                        'beneficiaries.U18Female',
                        'beneficiaries.U17Female',
                        'beneficiaries.employmentStatus',
                        'beneficiaries.referredby',
                        'beneficiaries.isActive',
                        'beneficiaries.created_at',
                    )
                    ->where('identification',$id)
                    ->first();
        if(!Empty($userData_actual)){
            $userData = $userData_actual;
        }else {
            $userData = $userData_perspective;
        }
        $payment = Payout::where('clientID',$id)->where('status', 1)->sum('payment');
        $activated = Account::where('beneficiary',$id)->first();
        if (empty($userData)) {
            alert()->warning('Encounted error with this record!','Oops!!');
            return Redirect::back();
        }
        return view('client.profile', [
            'userData' => $userData,
            'paymentDone'=>$payment,
            'activatedDate' => $activated,
        ]);
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteClient($id)
    {
        try {
            alert()->success('User has been deleted','Success!')->persistent('are you sure?')->autoclose(3500);
            Beneficiary::where('id', $id)
                ->update([
                    'isActive' => '0'
                ]);
            $this->ActivityLogs('Delete user','Beneficiary',$id);
            alert()->success('User has been deleted','Success!')->persistent('Close');
            return Redirect('/client');
        } catch (\Throwable $th) {
            alert()->error('System countered errors during operation, try or contact system admin!','Oops something wrong!');
            return Redirect::back();
        }
        
    }

    public function returnFix(Request $request)
    {
        // return $request;
        /**
         * return solar panel for fix
         */
        try {
            solarPanel::where('solarPanelSerialNumber', $request->solar)
                    ->update([
                        'moreInfo' => 2
                    ]);
            $client = DB::table('accounts')
                    ->join('beneficiaries','beneficiaries.identification','accounts.beneficiary')
                    ->where('productNumber',$request->solar)->first();
                    $message = 'Ubusabe bwo gusubiza umurasire ufite numero: '.$client->productNumber.' bwagenze neza \n uzamenyeshwa igihe uzaba umaze gukorwa, \n Murakoze';
            // $this->sendBulk($client->primaryPhone,$message);
            $this->ActivityLogs('report damaged solar','solarPanel','huuu');
            alert()->success('Solar Panel has reported back to belecom','Success');
            return Redirect('/client/actual');

        } catch (\Throwable $th) {
            alert()->error('System countered errors during operation, try or contact system admin!','Oops something wrong!');
            return Redirect::back();
        }
    }

    public function fixed(Request $request)
    {
        // return $request;
        /**
         * return solar panel for fix
         */
        try {
            solarPanel::where('solarPanelSerialNumber', $request->solar)
                    ->update([
                        'moreInfo' => 0
                    ]);
            $client = DB::table('accounts')
                    ->join('beneficiaries','beneficiaries.identification','accounts.beneficiary')
                    ->where('productNumber',$request->solar)->first();
                    $message = 'Usubijwe umurasire ufite numero: '.$client->productNumber.'\n Murakoze';
            // return $client->productNumber;
                    // $this->sendBulk($client->primaryPhone,$message);
            $this->ActivityLogs('give back fixed panel','solarPanel','kkkkk');
            alert()->success('Solar Panel has reported back to customer','Success');
            return Redirect('/client/actual');

        } catch (\Throwable $th) {
            alert()->error('System countered errors during operation, try or contact system admin!','Oops something wrong!');
            return Redirect::back();
        }
    }

    public function deactivate(Request $request)
    {
        try {
            /**
             * remove product from deactivated accounts
             */
            Account::where('productNumber',$request->solar)
                    ->update([
                                'productNumber' => NULL
                            ]);
            Payout::where('solarSerialNumber', $request->solar)->where('accountStatus',0)
                    ->update([
                        'accountStatus' => 1
                    ]);
            SolarPanel::where('solarPanelSerialNumber',$request->solar)
                        ->update([
                            'status' => 0,
                            'moreInfo' => 1
                        ]);
            $client = DB::table('accounts')
            ->join('beneficiaries','beneficiaries.identification','accounts.beneficiary')
            ->where('productNumber',$request->solar)->first();
            Beneficiary::where('identification', $client->identification)
                    ->update([
                        'isActive' => 3
                    ]);
            $message = 'Usubije burundu umurasire ufite numero: '.$request->solar.'\n murakoze gukoresha service za belecom.';
            $this->sendBulk($client->primaryPhone,$message);
            $this->ActivityLogs('deactivating a user', 'beneficiar,Solarpanel,Payout,Account',$client->identification);
            alert()->success('Solar Panel has reported back to belecom','Success');
            return Redirect('/client/actual');
        } catch (\Throwable $th) {
            alert()->error('System countered errors during operation, try or contact system admin!','Oops something wrong!');
            return Redirect::back();
        }
        
    }

    public function updateDate(Request $request)
    {
        $rules = array (
            'date' => 'required'
        );
        // return $request;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            alert()->error('Date input is required', 'Something Wrong!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
        Beneficiary::where('identification', $request->client)
                    ->update([
                        'created_at' => $request->date
                    ]);
        alert()->error('date Updated', 'Done!');
        return Redirect::back();
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

    public function sendBulk($number, $message)
    {
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
}