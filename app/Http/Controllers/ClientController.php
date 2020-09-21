<?php

namespace App\Http\Controllers;

use Redirect;
use Validator;
use SweetAlert;
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
            // 'villageName' => 'required',
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

        $client = new Beneficiary();
        $client->firstname = $request->firstname;
        $client->lastname =$request->lastname;
        $client->identification =$request->identification;
        $client->gender =$request->gender;
        $client->DOB =$request->DOB;
        $client->primaryPhone =$request->primaryPhone;
        $client->secondaryPhone =$request->secondaryPhone;
        $client->educationLevel =$request->educationLevel;
        $client->incomeSource =$request->incomeSource;
        $client->sourceOfEnergy =$request->sourceOfEnergy;
        $client->location =$request->location;
        $client->village =$request->village;
        $client->quarterName =$request->quarterName;
        $client->houseNumber =$request->houseNumber;
        $client->buildingMaterial =$request->buildingMaterial;
        $client->familyMember =$request->familyMember;
        $client->membersInSchool =$request->membersInSchool;
        $client->U18Male =$request->U18Male;
        $client->U17Male =$request->U17Male;
        $client->U18Female =$request->U18Female;
        $client->U17Female =$request->U17Female;
        $client->employmentStatus = $request->employmentStatus;
        $client->referredby = $request->referredby;
        $client->isActive = 1;
        // return $request;
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
