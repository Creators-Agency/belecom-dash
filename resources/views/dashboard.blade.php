@extends('layouts/wrapper')
@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Dashboard</h4>
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
                                <i class="mdi mdi-emoticon font-20 text-info"></i>
                                <p class="font-16 m-b-5">New Clients</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0">23</h1>
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
                                <i class="mdi mdi-image font-20 text-success"></i>
                                <p class="font-16 m-b-5">New Projects</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0">169</h1>
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
                                <i class="mdi mdi-currency-eur font-20 text-purple"></i>
                                <p class="font-16 m-b-5">New Invoices</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0">157</h1>
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
                                <i class="mdi mdi-poll font-20 text-danger"></i>
                                <p class="font-16 m-b-5">New Sales</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0">236</h1>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="card-title">Sales Analytics</h4>
                            </div>
                        </div>
                        <div class="mt-5">
                            {!! $analytics->container() !!}
                            {!! $analytics->script() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
