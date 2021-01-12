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
                        <h4 class="card-title">Clients</h4>
                        <div class="row">
                            <div class="col-sm-3 p-3">
                                Name: {{ $info->firstname.' '.$info->lastname }}
                            </div>
                            <div class="col-sm-3 p-3">
                                Tel:{{ $info->primaryPhone }}
                            </div>
                            <div class="col-sm-3 p-3">
                                Product Number:{{ $info->productNumber }}
                            </div>
                            <div class="col-sm-3 p-3">
                                location: {{ $info->location }}
                            </div>
                            <div class="col-sm-3 p-3">
                                payment Status:{{ $info->monthleft }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection