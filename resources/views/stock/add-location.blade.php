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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 pb-2">Add New Branch</h4>
                    <form class="needs-validation" method="POST" action="{{ route('CreateLocation') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="LocationName">Branch Name</label>
                                    <input type="text" class="form-control" id="LocationName"
                                        placeholder="Location Name" name="locationName"
                                        value="{{ old('locationName') }}" required>
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Supervisor">Supervisor</label>
                                    <select class="custom-select" name="supervisor" id="Supervisor" required>
                                        <option value="">Select Supervisor</option>
                                        @foreach($staffs as $staff)
                                        <option value="{{ ($staff->id) }}">
                                            {{ ($staff->firstname.' '.$staff->lastname) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">You must choose supervisor</div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Add Branch</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Location</h4>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Supervisor</th>
                                    <th>Registered date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($ifRecord>0)
                                @foreach($Locations as $location)
                                <tr>
                                    <td>{{ ($location->locationName) }}</td>
                                    <td>{{ ($location->locationCode) }}</td>
                                    <td>{{ ($location->supervisor) }}</td>
                                    <td>{{ date('D d M, Y', strtotime($location->created_at)) }}</td>
                                    <td>
                                        <a href="{{URL::to('stock/edit/'.$location->id.'/location')}}">
                                            <i class="far fa-edit text-primary p-2"></i>
                                        </a>
                                        <a href="{{URL::to('stock/delete/'.$location->id.'/location')}}">
                                            <i class="fas fa-trash text-danger p-2"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else

                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Serial Number</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Registered date</th>
                                    <th>Action</th>
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
<script type="text/javascript">
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endsection