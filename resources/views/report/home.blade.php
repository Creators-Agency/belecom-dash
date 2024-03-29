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
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r">
                                <a href="/report/actual" class="btn btn-success">actual clients</a>
                            </div>
                            <div class="col-md-3 col-xs-6 ">
                                <a href="/report/perspective" class="btn btn-success">perspective clients</a>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r">
                                <a href="/report/clients/payment/done" class="btn btn-success">List of Fully Paid
                                    clients</a>
                            </div>
                            <div class="col-md- col-xs-6 b-r">
                                <a href="/report/clients/amount/due" class="btn btn-success">Clients with amount due</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row ">
                            <div class="col-md-6 col-xs-6 b-r">
                                <a href="/report/faulty/product" class="btn btn-primary">faulty product</a>
                            </div>
                            <div class="col-md-6 col-xs-6 ">
                                <a href="/report/returned/product" class="btn btn-primary">returned product</a>
                            </div>
                            {{-- <div class="col-md-3 col-xs-6 b-r">
                                <a href="" class="btn btn-success">List of Fully Paid clients</a>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r">
                                <a href="" class="btn btn-success">Clients with amount due</a>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection