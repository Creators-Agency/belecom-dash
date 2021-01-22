@extends('layouts/wrapper')
@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title ml-2">Dashboard</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                <br>
                                <p class="text-muted">{{$userData->firstname.' '.$userData->lastname}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                                <br>
                                <p class="text-muted">{{$userData->primaryPhone}}
                                    @if($userData->secondaryPhone)
                                    / $userData->secondaryPhone
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>ID</strong>
                                <br>
                                <p class="text-muted">{{$userData->identification}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                                <br><?php 
                                        $location = \App\Models\AdministrativeLocation::where('id', $userData->location)->first();
                                    ?>
                                <p class="text-muted">{{$location->locationName}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="card-group">
                            <!-- Column -->
                            <!-- Column -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="text-center text-cyan">Amount to be paid</h4>
                                    <h2>1200</h2>
                                    <div class="row p-t-10 p-b-10">
                                        <!-- Column -->
                                        <div class="col text-center align-self-center">
                                            <div data-label="80%" class="css-bar m-b-0 css-bar-success css-bar-80"><i
                                                    class="display-6 mdi mdi-briefcase-check"></i></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <h4 class="font-medium m-b-0 text-success">Paid <br>
                                                12465</h4>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <h4 class="font-medium m-b-0 text-danger">Due <br>
                                                145</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                        </div>
                        <hr>
                        <!-- Column -->
                        <div class="row text-center">
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Contract</strong>
                                <br>
                                <p class="text-muted">
                                    <br>
                                <p>date</p>
                                <br>
                                <button type="button" class="btn btn-success btn-circle ">
                                    <i class="fa fa-check"></i>
                                </button>
                                </p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Product Activated</strong>
                                <br>
                                <p class="text-muted">
                                    <br>
                                <p>date</p>
                                <br>
                                <button type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-check"></i>
                                </button>
                                </p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Payment status</strong>
                                <br>
                                <p class="text-muted">
                                    <br>
                                <p>on going</p>
                                <br>
                                <button type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-check"></i>
                                </button>
                                </p>
                            </div>
                        </div>
                        <!-- Column -->
                        <hr>
                        <div class="row text-center">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                <br>
                                <p class="text-muted">{{$userData->firstname.' '.$userData->lastname}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                                <br>
                                <p class="text-muted">{{$userData->primaryPhone}}
                                    @if($userData->secondaryPhone)
                                    / $userData->secondaryPhone
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>ID</strong>
                                <br>
                                <p class="text-muted">{{$userData->identification}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                                <br><?php 
                                        $location = \App\Models\AdministrativeLocation::where('id', $userData->location)->first();
                                    ?>
                                <p class="text-muted">{{$location->locationName}}</p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                <br>
                                <p class="text-muted">{{$userData->firstname.' '.$userData->lastname}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                                <br>
                                <p class="text-muted">{{$userData->primaryPhone}}
                                    @if($userData->secondaryPhone)
                                    / $userData->secondaryPhone
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>ID</strong>
                                <br>
                                <p class="text-muted">{{$userData->identification}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                                <br><?php 
                                        $location = \App\Models\AdministrativeLocation::where('id', $userData->location)->first();
                                    ?>
                                <p class="text-muted">{{$location->locationName}}</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection