<?php

namespace App\Http\Controllers;
use Validator;
use SweetAlert;
use Redirect;
use Auth;

use Illuminate\Http\Request;

class WelcomController extends Controller
{

    public function register()
	{
		return view('auth.register');
	}

	public function Authlogin(Request $request) {
        $rules = array (
            'email' => 'required|min:6|max:255',
            'password' => 'required|min:6|max:255',
        );
        // return $request;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator)->withInput();
        }
        // return '';
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])){
            if(Auth::user()->accessLevel == 1) {
                return redirect('/');
            // } else {
            //     // return ;
            // }
            return redirect('/')->withErrors("Wrong Username or Password!");

        }

    }

}
