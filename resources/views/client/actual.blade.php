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
                        <h4 class="card-title">Actual clients</h4>
                        <div class="table-responsive">
                            <table id="file_export" class="table table-striped table-bordered display">
                                <thead>
                                    <tr>
                                        <th>Identification</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Location</th>
                                        <th>Phone</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr>
                                        <td>{{ ($client->identification) }}</td>
                                        <td>{{ ($client->firstname) }}</td>
                                        <td>{{ ($client->lastname) }}</td>
                                        <td>{{ ($client->gender) }}</td>
                                        <td>{{ ($client->location) }}</td>
                                        <td>{{ ($client->primaryPhone) }}</td>
                                        <td class="text-center">
                                            <a
                                                href="{{ URL::to('/client/'.$client->identification.'-'.strtotime($client->DOB).'/edit') }}">
                                                <i class="fas fa-edit text-primary p-2"></i>
                                            </a>
                                            <a href="{{ URL::to('/client/'.$client->id.'/delete') }}">
                                                <i class="fas fa-trash text-danger p-2"></i>
                                            </a>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Identification</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Location</th>
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
</div>
@endsection