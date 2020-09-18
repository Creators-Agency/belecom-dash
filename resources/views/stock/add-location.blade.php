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
                    <h4 class="card-title mb-5 pb-2">Update Stock</h4>
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                <label for="NumberOfSolar">Location Name</label>
                                <input type="text" class="form-control" id="NumberOfSolar" placeholder="Number of solar panel" required>
                                <div class="invalid-tooltip">
                                    This field shouldn't be empty
                                </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="administrativeLocation">Supervisor</label>
                                    <select class="custom-select" id="administrativeLocation" required>
                                        <option value="">Select Supervisor</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    <div class="invalid-tooltip">You must choose supervisor</div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Submit form</button>
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
                                    <th>Serial Number</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Registered date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>2011/04/25</td>
                                    <td>
                                        <i class="far fa-edit text-primary p-2"></i>
                                        <i class="fas fa-trash text-danger p-2"></i>
                                    </td>
                                </tr>
                                
                                </tr>
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