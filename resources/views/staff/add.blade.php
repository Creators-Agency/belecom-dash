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
            @include('message')
                <div class="card-body">
                    <h4 class="card-title mb-5 pb-2">Register staff</h4>
                    <form class="needs-validation" method="POST" action="{{ route('Register') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="FirstName">First Name</label>
                                    <input type="text" name="firstName" class="form-control" id="FirstName" placeholder="First Name" value="{{ old('firstName') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="LastName">Last Name</label>
                                    <input type="text" name="lastName" class="form-control" value="{{ old('lastName') }}" id="FirstName" placeholder="Last Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary" for='photo' type="button">Photo</button>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="photo">
                                            <label class="custom-file-label" for="photo">Choose Photo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Identification">National ID</label>
                                    <input type="number" name="identification" class="form-control" value="{{ old('identification') }}" id="Identification" placeholder="Identification" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Age">DOB</label>
                                    <input type="date" name="age" class="form-control" id="Age" value="{{ old('age') }}" placeholder="Birth date" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Gender</h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="GenderM" name="gender" value="1" required>
                                                <label class="custom-control-label" for="GenderM">Male</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="GenderF" name="gender" value="0" required>
                                                <label class="custom-control-label" for="GenderF">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="PrimaryNumber">Primary Number</label>
                                    <input type="number" name="primaryNumber" class="form-control" value="{{ old('primaryNumber') }}" id="PrimaryNumber" placeholder="Primary Number" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Email">Email</label>
                                    <input type="email" name="email" class="form-control" id="Email"  placeholder="Email" value="{{ old('email') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Password">Password</label>
                                    <input type="password" name="password" class="form-control" id="Email" placeholder="password" value="{{ old('secondaryNumber') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="CoPassword">Confirm Password</label>
                                    <input type="password" name="copassword" class="form-control" id="Email" placeholder="Confirm password" value="{{ old('secondaryNumber') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                        </div>
                        <button class="btn btn-primary ml-3" type="submit">Register staff</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registered Solar Panel Type</h4>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered" style="width: 100%">
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
                                @foreach($staffs as $staff)
                                <tr>
                                    <td>{{ ($staff->identification) }}</td>
                                    <td>{{ ($staff->firstname) }}</td>
                                    <td>{{ ($staff->lastname) }}</td>
                                    <td>{{ ($staff->gender) }}</td>
                                    <td>{{ ($staff->location) }}</td>
                                    <td>{{ ($staff->primaryPhone) }}</td>
                                    <td class="text-center">
                                        <a href="{{ URL::to('/staff/'.$staff->id.'/assign') }}" class="btn btn-primary">Assign</a>
                                        <a href="{{ URL::to('/staff/'.$staff->identification.'-'.strtotime($staff->DOB).'/edit') }}">
                                            <i class="fas fa-edit text-primary p-2"></i>
                                        </a>
                                        <a href="{{ URL::to('/staff/'.$staff->id.'/delete') }}">
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