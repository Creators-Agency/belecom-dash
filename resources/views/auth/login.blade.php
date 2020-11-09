@extends('layouts/wrapper')
@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background-color:#0d0f17;">
    <div class="auth-box">
        <div id="loginform">
            <div class="logo">
                <span class="db"><img src="{{ URL::asset('assets/images/logo.jpg') }}" alt="logo"
                        style="width:80px;" /></span>
                <h5 class="font-medium m-b-20">Sign In</h5>
            </div>
            <!-- Form -->
            <div class="row">
                <div class="col-12">
                    @include('message')
                    <form class="form-horizontal m-t-20" method="POST" id="loginform" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-lg" name='email' placeholder="Email"
                                aria-label="Email" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                            </div>
                            <input type="password" class="form-control form-control-lg" name="password"
                                placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i
                                            class="fa fa-lock m-r-5"></i> Forgot password?</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="recoverform">
            {{--<div class="logo">
                <span class="db"><img src="../../assets/images/logo-icon.png" alt="logo" /></span>
                <h5 class="font-medium m-b-20">Recover Password</h5>
                <span>Enter your Email and instructions will be sent to you!</span>
            </div>--}}
            <div class="row m-t-20">
                <!-- Form -->
                <form class="col-12" action="index.html">
                    <!-- email -->
                    <div class="form-group row">
                        <div class="col-12">
                            <input class="form-control form-control-lg" type="email" required="" placeholder="Username">
                        </div>
                    </div>
                    <!-- pwd -->
                    <div class="row m-t-20">
                        <div class="col-12">
                            <button class="btn btn-block btn-lg btn-danger" type="submit" name="action">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection