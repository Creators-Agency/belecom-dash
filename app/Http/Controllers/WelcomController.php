<?php

namespace App\Http\Controllers;
use Validator;
use SweetAlert;
use Redirect;
use Auth;

use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermission;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class WelcomController extends Controller
{

    public function register()
	{
        $check_user = User::Where('type', 1)
                        ->count();
        if ($check_user != 0) {
            return redirect('/');
        }
		return view('auth.register');
    }
    
    public function staffSave(Request $request)
    {
        // return $request;
        $rules = array (
            'firstname' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'copassword' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return 'fd';
            return Redirect::back()->withErrors($validator)->withInput();
        }
        if($request->password !== $request->copassword){
            alert()->error('Oops', 'Password Issues');
            return Redirect::back()->withInput();
        }
        $check_user = User::Where('email', $request->email)
                                ->count();
        if ($check_user == 0) {
            
            // return $request;
            $staff = new User();
            $staff->firstname = $request->firstname;
            $staff->email =$request->email;
            $staff->password = Hash::make($request->password);
            $staff->status = 1;
            $staff->type = 1;


            if($staff->save()){
                $get = User::where('email',$request->email)->first();
                $perm = Permission::get();
                foreach ($perm as $key) {
                    $permission = new UserPermission();
                    $permission->userID = $get->id;
                    $permission->permissionID = $key->id;
                    $permission->save();
                }
                
                // alert()->success('New User is registered successfuly', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to Register new staff member', 'Oops');
                return Redirect::back()->withInput();
            }
        }
    }

}