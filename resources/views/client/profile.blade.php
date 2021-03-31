@extends('layouts/wrapper')
@section('meta')
<title>
    <?php 
try {
    echo $pageTitle;
} catch (\Throwable $th) {
    echo 'Belecom';
}?>

</title>
@endsection
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

use App\Models\SolarPanel;

$location = \App\Models\AdministrativeLocation::where('id', $userData->location)->first();
                                    ?>
                                <p class="text-muted">{{$location->locationName}}</p>
                            </div>
                        </div>
                        <br>
                        <div class="row text-center">
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Village Name</strong>
                                <br>
                                <p class="text-muted">{{$userData->village}}</p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Quarter Name</strong>
                                <br>
                                <p class="text-muted">
                                    {{$userData->quarterName}}
                                </p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>House Number</strong>
                                <br>
                                <p class="text-muted">{{$userData->houseNumber}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="card-group">
                            <!-- Column -->
                            <!-- Column -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <h7 class="text-center text-cyan">Product Number</h7>
                                    <?php

                                        if ($userData->isActive == 3) {
                                        $productNumber = \App\Models\Account::where('beneficiary',$userData->identification)->first();
                                        $solarPanel = \App\Models\SolarPanel::where('solarPanelSerialNumber',$productNumber->productNumber)->first();
                                        $solarPanelType = \App\Models\SolarPanelType::where('id',$solarPanel->solarPanelType)->first();
                                        
                                        if ($productNumber->loanPeriod != 36) {
                                            $toPayMonth = $productNumber->loan/$productNumber->loanPeriod;
                                            $totalAmount = $solarPanelType->price;
                                            $paid = $totalAmount-$productNumber->loan;
                                            // $perc = ($paid * 100)/$totalAmount;
                                        }else{
                                            $totalAmount = $solarPanelType->price ;
                                            $paid = $totalAmount- $paymentDone;
                                            // $perc = ($paid * 100)/$totalAmount;
                                        }
                                        }
                                    ?>
                                    <h1>
                                        @if(!Empty($productNumber))
                                        #{{$productNumber->productNumber}}
                                        <br>
                                        @if($solarPanel->moreInfo == 2)
                                        <form action="{{ route('fixed') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" value="{{$productNumber->productNumber}}" name="solar">
                                            <button type="submit" class="btn btn-warning">
                                                Re-assign
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('returnFix') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" value="{{$productNumber->productNumber}}" name="solar">
                                            <button type="submit" class="btn btn-warning">
                                                Return Product for fix
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('deactivate') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" value="{{$productNumber->productNumber}}" name="solar">
                                            <button type="submit" class="btn btn-danger">
                                                Deactivate account
                                            </button>
                                        </form>
                                        @else
                                        <button type="button" class="btn btn-danger btn-circle">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        @endif
                                    </h1>
                                    <h7 class="text-center text-cyan">Amount to be paid</h7>
                                    <h2>@if(!Empty($productNumber))
                                        {{number_format($totalAmount)}} Frw
                                        @else
                                        <button type="button" class="btn btn-danger btn-circle">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        @endif
                                    </h2>
                                    <div class="row p-t-10 p-b-10">
                                        <!-- Column -->
                                        <div class="col text-center align-self-center">
                                            <div data-label="80%" class="css-bar m-b-0 css-bar-success css-bar-90"><i
                                                    class="display-6 mdi mdi-briefcase-check"></i></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <h4 class="font-medium m-b-0 text-success">Paid <br>
                                                {{number_format($paymentDone)}} Frw
                                            </h4>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <h4 class="font-medium m-b-0 text-danger">Due <br>
                                                @if(!Empty($productNumber))
                                                {{number_format($totalAmount-$paymentDone)}} Frw
                                                @endif
                                            </h4>
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
                                <p>
                                    @if($userData->created_at == '0000-00-00 00:00:00')
                                <form action="{{route('updateDate')}}" method="POST">
                                    @csrf
                                    <input type="date" name="date" class="form-control" placeholder="set contract date">
                                    <input type="hidden" name="client" class="form-control"
                                        value="{{$userData->identification}}">
                                    <br>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </form>
                                @else
                                {{$userData->created_at}}
                                <br>
                                <button type="button" class="btn btn-success btn-circle ">
                                    <i class="fa fa-check"></i>
                                </button>
                                @endif

                                </p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Product Activated</strong>
                                <br>
                                <p class="text-muted">
                                    <br>
                                <p>
                                    @if(!Empty($productNumber))
                                    {{$activatedDate->created_at}}
                                    <br>
                                    <button type="button" class="btn btn-success btn-circle">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @else
                                    <br>
                                    <button type="button" class="btn btn-warning btn-circle">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Payment status</strong>
                                <br>
                                <p class="text-muted">
                                    <br>
                                <p>
                                    @if(!Empty($productNumber))
                                    on going
                                    <br>
                                    <button type="button" class="btn btn-warning btn-circle">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @else
                                    Not product
                                    <br>
                                    <button type="button" class="btn btn-danger btn-circle">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    @endif
                                </p>

                            </div>
                        </div>
                        <!-- Column -->
                        <hr>
                        <div class="row text-center">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Educational Level</strong>
                                <br>
                                <p class="text-muted">
                                    sec
                                </p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Employment Status</strong>
                                <br>
                                <p class="text-muted">Employment</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Source of Energy before</strong>
                                <br>
                                <p class="text-muted">energy</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Roof Material</strong>
                                <br>
                                <p class="text-muted">Iron</p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-sm-12">
                                <h5 class="m-5">Family Background</h5>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Number of family Member</strong>
                                <br>
                                <p class="text-muted">3</p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Family Member In School</strong>
                                <br>
                                <p class="text-muted">1</p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Number of Female Under 17</strong>
                                <br>
                                <p class="text-muted">1</p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-4 col-xs-6"> <strong>Number of Female above 18</strong>
                                <br>
                                <p class="text-muted">7</p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Number of Male Under 17</strong>
                                <br>
                                <p class="text-muted">3</p>
                            </div>
                            <div class="col-md-4 col-xs-6 b-r"> <strong>Number of Male above 18</strong>
                                <br>
                                <p class="text-muted">1</p>
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