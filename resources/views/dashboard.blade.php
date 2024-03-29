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

            <!-- About clients -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <i class="mdi mdi-emoticon font-20 text-info"></i>
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">New perspective
                                    Clients in This week</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0 f-size-1">{{ ($latestPerspectives) }}</h1>
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
                                <i class="mdi mdi-emoticon font-20 text-info"></i>
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">Total Number of
                                    Perspectives Clients</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0 f-size-1">{{ ($perspectives) }}</h1>
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
                                <i class="mdi mdi-emoticon font-20 text-info"></i>
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">New Actual
                                    Clients in This week</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0 f-size-1">{{ ($latestActualClient) }}</h1>
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
                                <i class="mdi mdi-emoticon font-20 text-info"></i>
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">Total Number of
                                    Actual all clients</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0 f-size-1">{{ ($actuals) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <i class="mdi mdi-currency-eur font-20 text-purple"></i>
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">Total Loans
                                    Amount</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">
                                    {{ ($loanAmount) }} Frw
                                </h1>
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
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px"> Paid
                                    Installment Amount</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">
                                    {{ ($latestSalesAmount) }} frw
                                </h1>
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
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">Registered
                                    Solar Panels</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">
                                    {{ ($panels) }}
                                </h1>
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
                                <p class="font-16 m-b-5" style="font-size: 10px !important;padding: 3px">Number of our
                                    Branch</p>
                            </div>
                            <div class="col-5">
                                <h1 class="font-light text-right mb-0" style="font-size: 12px !important;">
                                    {{ ($locations) }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="card-title">Product sales analytics</h4>
                            </div>
                        </div>
                        <div class="mt-5">
                            {!! $loans->container() !!}
                            {!! $loans->script() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="card-title">Loan payments analytics</h4>
                            </div>
                        </div>
                        <div class="mt-5">
                            {!! $payments->container() !!}
                            {!! $payments->script() !!}
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