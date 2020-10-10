<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use App\Models\User;
use App\Models\Permission;

use DB;
use Redirect;
use Validator;
use SweetAlert;

class StaffController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $Get_staff = User::where('status',1)
                        ->get();
        return view('staff.staff',[
            'staffs' => $Get_staff
        ]);
    }
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
            
            // return $request;
            $staff = new User();
            $staff->firstname = $request->firstName;
            $staff->lastname =$request->lastName;
            $staff->nationalID =$request->identification;
            $staff->gender =$request->gender;
            $staff->DOB =$request->age;
            $staff->phone = $request->primaryNumber;
            $staff->email =$request->email;
            $staff->password = Hash::make($request->password);
            $staff->status = 1;
            if($staff->save()){
                alert()->success('New User is registered successfuly', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to Register new staff member', 'Oops');
                return Redirect::back()->withInput();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editStaff()
    {
        return 'edit Staff';
    }

    public function permissionStaff($id)
    {
        // return $id;
        $data = User::where('nationalID', $id)->where('status', 1)->first();
        return $permission = DB::table('permissions')
                        ->join('user_permissions','permissions.id','=','user_permissions.permissionID')
                        ->get();
        return view('staff.permission',[
            'staff' => $data,
            'permsssion'
        ]);
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