<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Redirect;
use Validator;
use SweetAlert;
use Auth;
use DB;
use App\Models\Stock;
use App\Models\User;
use App\Models\SolarPanel;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\SolarPanelType;
use App\Models\AdministrativeLocation;
class StockController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    // status default for alert
    public $status = 0;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         *
         * Getting number of number of all solar panel
         *
         */
        $unassigned = SolarPanel::where('status', 0)
                                ->get();
        $assigned = SolarPanel::where('status', 1)
                                ->get();
        $returned = SolarPanel::where('status', 2)
                                ->get();
        $faulty = SolarPanel::where('status', 3)
                                ->get();
        $undermaintenance = SolarPanel::where('status', 4)
                                ->get();
        $Stolen = SolarPanel::where('status', 5)
                                ->get();
        $branchs = AdministrativeLocation::where('status',1)->get();
        $data = [];
        $get_data = [];
        foreach ($branchs as $branch) {
            $get = SolarPanel::where('location', $branch->id)->get();
            $price = 0;
            foreach ($get as $value) {
                $type = SolarPanelType::where('id',$value->solarPanelType)->first();
                $price = $price + $type->price;
            }
            $get_data['location'] = $branch->locationName;
            $get_data['product'] = count($get);
            $get_data['price'] = number_format($price);
            array_push($data, $get_data);
        }
        // return $data;
        $number = SolarPanel::get();
        $amount = 0;
        foreach ($number as $key) {
            $price = SolarPanelType::where('id',$key->solarPanelType)
                                    ->sum('price');
            $amount = $amount + $price;

        }

        return view('stock.stock',[
            'numberOfSolarAssigned' =>number_format(count($assigned)),
            'numberOfSolarUnAssigned' =>number_format(count($unassigned)),
            'numberOfSolarReturned' =>number_format(count($returned)),
            'faulty' =>number_format(count($faulty)),
            'undermaintenance' =>number_format(count($undermaintenance)),
            'Stolen' =>number_format(count($Stolen)),
            'amount' => number_format($amount),
            'location_data' => $data
        ]);
    }

    public function addNewItem()
    {

        $Get_location = AdministrativeLocation::orderBy('id','DESC')
                        ->where('status',1)
                        ->get();
        $Get_solarType = SolarPanelType::orderBy('id','DESC')
                        ->where('isActive',1)
                        ->get();
        return view('stock.add-item',[
            'Locations' => $Get_location,
            'SolarTypes' => $Get_solarType
        ]);
    }

    public function addNewLocation()
    {
        $staff = User::where('type',0)->where('status', 1)->get();
        if(count($staff) == 0){
            alert()->warning('No Supervisor available','Alert')->autoclose(4500);;
            return redirect('/staff/register');
        }
        $Get_location = AdministrativeLocation::orderBy('id','DESC')
                        ->where('status',1)
                        ->get();
        $staff = User::where('type',0)->where('status', 1)->get();
        return view('stock.add-location', [
            'Locations' => $Get_location,
            'ifRecord' => count($Get_location),
            'staffs' => $staff
        ]);
    }

    public function addNewType()
    {
        $Get_solarType = SolarPanelType::orderBy('id','DESC')
                        ->where('isActive',1)
                        ->get();
        return view('stock.add-solar-type', [
            'SolarTypes' => $Get_solarType,
            'ifRecord' => count($Get_solarType)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPanel()
    {
        $solarPanel = DB::table('solar_panels')
                        ->join('solar_panel_types','solar_panel_types.id','solar_panels.solarPanelType')
                        ->get();
        // return $solarPanel;
        return view('stock.product',[
            'panels' => $solarPanel
        ]);
    }

    public function viewOwner($Product)
    {
        $productData = DB::table('accounts')
                ->join('beneficiaries','beneficiaries.identification','Accounts.beneficiary')
                ->where('accounts.productNumber',$Product)
                ->first();
        return view('stock.product-owner',[
            'info' => $productData
        ]);

    }

    /**
     * Store a newly created SolarType in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveType(Request $request)
    {
        /*--------- Validating Data -------------------*/
        $rules = array (
            'SolarTypeName' => 'required',
            'SolarTypePrice' => 'required|max:7',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $check_solartype = SolarPanelType::where('solarTypeName', $request->SolarTypeName)
                                            ->count();
        /**
         * Check if this type exist in DB.
         */
        if ($check_solartype == 0) {

            /*----------- Saving New solar type -------------*/
            $solarType = new SolarPanelType();
            $solarType->solarTypeName = $request->SolarTypeName;
            $solarType->price = $request->SolarTypePrice;
            $solarType->isActive = 1;
            $solarType->doneBy = Auth::User()->id;

            // if success
            if ($solarType->save()) {

                /*============== Updating Activity Logs =========*/
                $Get_solarType = SolarPanelType::orderBy('id','DESC')->first();
                $this->ActivityLogs('New','Solar Panel Type', $Get_solarType->id);
                $status = 1;
            }

            // check and alert if succed
            if ($status === 1) {
               alert()->success('Success', 'New Solar panel type has been added!');
               return Redirect()->back();
            }
            else{
                // failed
                alert()->error('Oops', 'Something Wrong!');
                return Redirect()->back()->withErrors($validator)->withInput();
            }
        }else{
            alert()->error('Type exist, try with different name','Operation Failed!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveLocation(Request $request)
    {
        /*--------- Validating Data -------------------*/
        $rules = array (
            'locationName' => 'required',
            'supervisor' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $check_Location = AdministrativeLocation::where('locationName', $request->locationName)
                                            ->count();
        /**
         * Check if the Location exist in DB.
         */
        if ($check_Location == 0) {

            /*----------- Saving New Location -------------*/
            $location = new AdministrativeLocation();
            $location->locationName = $request->locationName;
            $location->supervisor = $request->supervisor;
            $location->locationCode =  strtotime(date('Y-m-d')).rand(100,999);
            $location->doneBy = Auth::User()->id;

            // if success
            if ($location->save()) {

                /*============== Updating Activity Logs =========*/
                $Get_location = AdministrativeLocation::orderBy('id','DESC')->first();
                $this->ActivityLogs('New','Administrative Location', $Get_location->id);
                $status = 1;
            }

            // check and alert if succed
            if ($status === 1) {
               alert()->success('Success', 'New Location has been added!');
               return Redirect()->back();
            }
            else{
                // failed
                alert()->error('Oops', 'Something Wrong!');
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }else{
            alert()->error('Location exist, try with different name','Operation Failed!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveItem(Request $request)
    {
        /*--------- Validating Data -------------------*/
        $rules = array (
            'solarPanelType' => 'required',
            'location' => 'required',
            'numberOfSolar' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }


        /*----------- Saving New solar panel -------------*/
        for ($i=1; $i <= $request->numberOfSolar ; $i++) {

            $solar = new SolarPanel();
            $solar->solarPanelType = $request->solarPanelType;
            $solar->location = $request->location;
            $solar->doneBy = Auth::User()->id;

            // generating serial nuber
            $solar->solarPanelSerialNumber = strtotime(date('Y-m-d')).rand(100,999);
            if ($solar->save()) {
                /*============== Updating Activity Logs =========*/
                $Get_solar = SolarPanel::orderBy('id','DESC')->first();
                $this->ActivityLogs('New','Solar Panel', $Get_solar->id);
            }
            if ($i == $request->numberOfSolar ) {
                $status = 1;
            }
        }

        // check and alert if succe
        if ($status === 1) {
           alert()->success('Success', 'Operation is successful!');
           return Redirect('/stock');
        }
        else{
            // failed
            alert()->error('Oops', 'Something Wrong!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function editType($id)
    {
        $Get_solarType = SolarPanelType::where('id',$id)->first();
        // check if the record exist
        if ($Get_solarType) {
            return view('stock.edit-type',[
                'singleType' => $Get_solarType
            ]);
        }
        alert()->danger('Oops!', 'No Record Found');
        return Redirect('/stock/new/solar/type');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editLocation($id)
    {
        $Get_solarType = SolarPanelType::where('id',$id)->first();
        // check if the record exist
        if ($Get_solarType) {
            return view('stock.edit-type',[
                'singleType' => $Get_solarType
            ]);
        }
        alert()->danger('No Record Found!', 'Oops!');
        return Redirect('/stock/new/solar/type');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editItem($id)
    {
        $Get_solarType = SolarPanelType::where('id',$id)->first();
        // check if the record exist
        if ($Get_solarType) {
            return view('stock.edit-type',[
                'singleType' => $Get_solarType
            ]);
        }
        alert()->danger('No Record Found!', 'Oops!');
        return Redirect('/stock/new/solar/type');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateType(Request $request)
    {
        /*--------- Validating Data -------------------*/
        $rules = array (
            'SolarTypeName' => 'required',
            'SolarTypePrice' => 'required|max:7',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $update = SolarPanelType::where('id', $request->KeyToEdit)
                ->update([
                    'solarTypeName' => $request->SolarTypeName,
                    'price' => $request->SolarTypePrice,
                ]);

        // if success
        if ($update) {
            alert()->success('saved with success!', 'Done!');
            return Redirect()->back();
        }
        alert()->danger('Failed to save', 'Oops!');
        return Redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSolar($serialNumber)
    {
        return $data = SolarPanel::where('solarPanelSerialNumber', $serialNumber)->first();
        return view('stock.edit-product');
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
