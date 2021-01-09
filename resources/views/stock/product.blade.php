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
                                        <th>Serial Number</th>
                                        <th>Type of Solar Panel</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($panels as $panel)
                                    <tr>
                                        <td>{{ ($panel->solarPanelSerialNumber) }}</td>
                                        <td>{{ ($panel->solarTypeName) }}</td>
                                        <td>
                                            @if ($panel->status === 0)
                                            Un-assigned
                                            @elseif($panel->status === 1)
                                            Sold
                                            @elseif($panel->status === 2)
                                            Returned
                                            @else
                                            returned
                                            @if($panel->status === 3)
                                            Faulty
                                            @elseif($panel->status === 4)
                                            undermaintenance
                                            @else
                                            Stolen
                                            @endif
                                            @endif
                                        </td>
                                        <td class=" text-center">
                                            @if ($panel->status != 1)
                                            <a href="{{ URL::to('/stock/panel/'.$panel->solarPanelSerialNumber.'/edit') }}"
                                                class="btn btn-primary">

                                                Edit
                                            </a>
                                            @else
                                            <a href="{{ URL::to('/stock/view/owner/'.$panel->solarPanelSerialNumber) }}"
                                                class="btn btn-primary">

                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Serial Number</th>
                                        <th>Type of Solar Panel</th>
                                        <th>Status</th>
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