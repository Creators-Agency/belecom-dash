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
                        <h4 class="card-title">Payment List</h4>
                        <div class="table-responsive">
                            <table id="file_export" class="table table-striped table-bordered display">
                                <thead>
                                    <tr>
                                        <th>Clients Name</th>
                                        <th>Transaction ID</th>
                                        <th>Transaction Amount</th>
                                        <th>Phone Number</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ ($payment->clientNames) }}</td>
                                        <td>{{ ($payment->transactionID) }}</td>
                                        <td>{{ ($payment->payment) }}</td>
                                        <td>{{ ($payment->clientPhone) }}</td>
                                        <td>{{ ($payment->created_at) }}</td>
                                        <td>{{ ($payment->status == 1 ? 'success':'fail') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Clients Name</th>
                                        <th>Transaction ID</th>
                                        <th>Transaction Amount</th>
                                        <th>Phone Number</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection