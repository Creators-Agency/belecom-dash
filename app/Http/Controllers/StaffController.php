<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Redirect;
use Validator;
use SweetAlert;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addStaff()
    {
        $Get_staff = User::where('status',1)
                        ->get();
        return view('staff.add',[
            'staffs' => $Get_staff
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function staffSave(Request $request)
    {
        $rules = array (
            'firstName' => 'required',
            'lastName' => 'required',
            'identification' => 'required',
            'age' => 'required',
            'primaryNumber' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'password' => 'required|min:8',
            'copassword' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            alert()->error('Oops', 'Something Wrong!');
            return Redirect::back()->withErrors($validator)->withInput();
        }
        if($request->password !== $request->copassword){
            alert()->error('Oops', 'Password Issues');
            return Redirect::back()->withInput();
        }
        $check_user = User::where('nationalID', $request->identification)
                                ->orWhere('phone', $request->primaryNumber)
                                ->orWhere('email', $request->email)
                                ->count();
        if ($check_user == 0) {
            return $request;
        }
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
