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
                    <form class="needs-validation" method="POST" action="{{ route('CreateType') }}" novalidate>
                         @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                <label for="SolarType">Solar Panel Type</label>
                                <input type="text" class="form-control" id="SolarType" placeholder="Name of Solar Type" value="{{ old('SolarTypeName') }}" name="SolarTypeName" required>
                                <div class="invalid-tooltip">
                                    This field shouldn't be empty
                                </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                <label for="Price">Price</label>
                                <input type="number" class="form-control" id="Price" placeholder="0" name="SolarTypePrice" value="{{ old('SolarTypePrice') }}" required>
                                <div class="invalid-tooltip">
                                    This field shouldn't be empty
                                </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Registered Solar Panel Type</h4>
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Name </th>
                                            <th>Price</th>
                                            <th>Done By</th>
                                            <th>Registered date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($ifRecord>0)
                                        @foreach($SolarTypes as $singleType)
                                        <tr>
                                            <td>{{ ($singleType->solarTypeName) }}</td>
                                            <td>{{ ($singleType->price) }}</td>
                                            <td>{{ ($singleType->doneBy) }}</td>
                                            <td>{{ date('D d M, Y', strtotime($singleType->created_at)) }}</td>
                                            <td>
                                                <a href="{{URL::to('stock/edit/'.$singleType->id.'/type')}}">
                                                    <i class="far fa-edit text-primary p-2"></i>
                                                </a>
                                                <a href="{{URL::to('stock/delete/'.$singleType->id.'/type')}}">
                                                    <i class="fas fa-trash text-danger p-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                            <!-- No Record! -->
                                        @endif
                                        
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name </th>
                                            <th>Price</th>
                                            <th>Done By</th>
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