@extends('layouts/wrapper')
@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background-color:#0d0f17;">
    <div class="auth-box">
        <div id="loginform">
            <div class="logo">
                <span class="db"><img src="{{ URL::asset('assets/images/logo.jpg') }}" alt="logo"
                        style="width:80px;" /></span>
                <h5 class="font-medium m-b-20">Register New Staff</h5>
            </div>
            <!-- Form -->
            <div class="row">
                <div class="col-12">
                    <form class="form-horizontal m-t-20" action="{{ route('createBack') }}" method="POST">
                        @csrf
                        <div class="form-group row ">
                            <div class="col-12 ">
                                <input class="form-control form-control-lg" name='firstname' type="text" required=" "
                                    placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 ">
                                <input class="form-control form-control-lg" type="text" name='email' required=" "
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 ">
                                <input class="form-control form-control-lg" type="password" name="password" required=" "
                                    placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 ">
                                <input class="form-control form-control-lg" name="copassword" type="password"
                                    required=" " placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="form-group text-center ">
                            <div class="col-xs-12 p-b-20 ">
                                <button class="btn btn-block btn-lg btn-info " type="submit ">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection