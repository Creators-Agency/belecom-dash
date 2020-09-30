@extends('layouts/wrapper')
@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title"><a href="/">Dashboard</a> / Stock</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <!-- <i class="mdi mdi-emoticon font-20 text-info"></i> -->
                                <p class="font-16 m-b-5" style="font-size: 12px !important;padding: 3px">Non assigned Item</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">{{ ($numberOfSolarUnAssigned) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <!-- <i class="mdi mdi-image font-20 text-success"></i> -->
                                <p class="font-16 m-b-5" style="font-size: 12px !important;padding: 3px">Assigned item</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">{{ ($numberOfSolarAssigned) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <!-- <i class="mdi mdi-currency-eur font-20 text-purple"></i> -->
                                <p class="font-16 m-b-5" style="font-size: 12px !important;padding: 3px">Returned Item</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">{{ ($numberOfSolarReturned) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <!-- <i class="mdi mdi-poll font-20 text-danger"></i> -->
                                <p class="font-16 m-b-5" style="font-size: 12px !important;padding: 3px">Amount of all item</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">{{ ($amount) }} RW</h1>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
        </div>
        <!-- stock per location -->
        <div class="row">
            <!-- column -->
            <div class="col-lg-6 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Kigali</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row align-items-center">
                            <div class="col-xs-12 col-md-6">
                                <h3 class="m-b-0 font-light">Total Item</h3>
                                <span class="font-14 text-muted">34</span>
                            </div>
                            <div class="col-xs-12 col-md-6 align-self-center display-6 text-info text-right">$3,690</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="card-title mb-0">Nyabiheke</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row align-items-center">
                            <div class="col-xs-12 col-md-6">
                                <h3 class="m-b-0 font-light">Total Item</h3>
                                <span class="font-14 text-muted">67</span>
                            </div>
                            <div class="col-xs-12 col-md-6 align-self-center display-6 text-info text-right">$3,690</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
