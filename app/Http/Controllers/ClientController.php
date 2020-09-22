<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use SweetAlert;
use App\Models\Referee;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Models\AdministrativeLocation;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('client.add');
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
            'secondaryNumber' => 'required',
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
            return $rules;
            alert()->error('Oops', 'Something Wrong!');
            return Redirect::back()->withErrors($validator)->withInput();
        }

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
        $client->save();

        // if he/she heard about from someone
        if ($request->refer = 1) {
            $refer = new Referee();
            $refer->refereeName = $request->names;
            $refer->refereeID = $request->identityReferee;
            $refer->referrePhone = $request->refereeNumber;
            $refer->relationship = $request->relationship;
            $refer->save();
        }
        alert()->success('yes','done');
        return $request;
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
}
