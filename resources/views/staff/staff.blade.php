@extends('layouts/wrapper')
@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title ml-2">Dashboard/ Staff</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Solar Panel Type</h4>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffs as $staff)
                                <tr>
                                    <td>{{ ($staff->firstname) }}</td>
                                    <td>{{ ($staff->lastname) }}</td>
                                    <td>{{ ($staff->gender) }}</td>
                                    <td>{{ ($staff->phone) }}</td>
                                    <td class="text-center">
                                        <a href="{{ URL::to('/staff/'.$staff->nationalID.'-'.md5($staff->id).'/permission') }}"
                                            class="btn btn-primary">Permission</a>
                                        <a
                                            href="{{ URL::to('/staff/'.$staff->nationalID.'-'.md5($staff->id).'/edit') }}">
                                            <i class="fas fa-edit text-primary p-2"></i>
                                        </a>
                                        <a
                                            href="{{ URL::to('/staff/'.$staff->nationalID.'-'.md5($staff->id).'/delete') }}">
                                            <i class="fas fa-trash text-danger p-2"></i>
                                        </a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection