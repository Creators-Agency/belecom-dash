<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use App\Models\Stock;
use App\Models\SolarPanel;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\SolarPanelType;
use App\Models\AdministrativeLocation;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stock.stock');
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
        $Get_location = AdministrativeLocation::orderBy('id','DESC')
                        ->where('status',1)
                        ->get();
        return view('stock.add-location', [
            'Locations' => $Get_location,
            'ifRecord' => count($Get_location)
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
    public function create()
    {
        //
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


        /*----------- Saving New solar type -------------*/
        $solarType = new SolarPanelType();
        $solarType->solarTypeName = $request->SolarTypeName;
        $solarType->price = $request->SolarTypePrice;
        $solarType->isActive = 1;
        $solarType->doneBy = 1;

        // if success
        if ($solarType->save()) {

            /*============== Updating Activity Logs =========*/
            $Get_solarType = SolarPanelType::orderBy('id','DESC')->first();
            $this->ActivityLogs('New','Solar Panel Type', $Get_solarType->id);
        }

        // failed 
        // alert()->danger('Oops!', 'Failed to save');
        return Redirect::back()->withErrors($validator)->withInput();
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


        /*----------- Saving New Location -------------*/
        $location = new AdministrativeLocation();
        $location->locationName = $request->locationName;
        $location->supervisor = $request->supervisor;
        $location->doneBy = 1;

        // if success
        if ($location->save()) {

            /*============== Updating Activity Logs =========*/
            $Get_location = AdministrativeLocation::orderBy('id','DESC')->first();
            $this->ActivityLogs('New','Administrative Location', $Get_location->id);
        }

        // failed 
        // alert()->danger('Oops!', 'Failed to save');
        return Redirect::back()->withErrors($validator)->withInput();
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
        $solar = new SolarPanel();
        $solar->solarPanelType = $request->solarPanelType;
        $solar->location = $request->location;
        $solar->doneBy = 1;
        $Get_location = AdministrativeLocation::orderBy('id','DESC')
                        ->where('status',1)
                        ->first();
        for ($i=0; $i < $request->numberOfSolar ; $i++) { 
            $solar->solarPanelSerialNumber =$Get_location->locationCode.date('Y/m/d').'/'.$i;
            $solar->save(); 

            if ($location->save()) {
                /*============== Updating Activity Logs =========*/
                $Get_solar = SolarPanel::orderBy('id','DESC')->first();
                $this->ActivityLogs('New','Solar Panel', $Get_solar->id);
            }
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
        // alert()->danger('Oops!', 'No Record Found');
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
            // alert()->success('Done!', 'saved with success!');
            return Redirect::back();
        }
        // alert()->danger('Oops!', 'Failed to save');
        return Redirect::back();

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
                // alert()->success('Done!', 'saved with success!');
                return Redirect::back();
            }
        }
        // else report error!
        // alert()->danger('Oops!', 'Failed to save');
         return Redirect::back();
    }
}
