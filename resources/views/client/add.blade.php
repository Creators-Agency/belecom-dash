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
                    <h4 class="card-title mb-5 pb-2">Register Client</h4>
                    <form class="needs-validation" method="POST" action="{{ route('CreateItem') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="FirstName">First Name</label>
                                    <input type="text" name="firstName" class="form-control" id="FirstName" placeholder="First Name" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="LastName">Last Name</label>
                                    <input type="text" name="lastName" class="form-control" id="FirstName" placeholder="Last Name" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Identification">Identification</label>
                                    <input type="number" name="identification" class="form-control" id="Identification" placeholder="Identification" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Age">Age</label>
                                    <input type="number" name="age" class="form-control" id="Age" placeholder="Client's Age" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="PrimaryNumber">Primary Number</label>
                                    <input type="number" name="primaryNumber" class="form-control" id="PrimaryNumber" placeholder="Primary Number" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="SecondaryNumber">Secondary Number</label>
                                    <input type="number" name="secondaryNumber" class="form-control" id="SecondaryNumber" placeholder="Secondary Number" required>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Location">Location</label>
                                    <select class="custom-select" name="location" id="Location" required>
                                        <option value="">Choose Location</option>
                                        <option value="1">one</option>
                                    </select>
                                    <div class="invalid-tooltip">You must select valid Location</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="QuarterName">Quarter Name</label>
                                    <input type="text" name="quarterName" class="form-control" id="QuarterName" placeholder="Quarter Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="HouseNumber">House Number</label>
                                    <input type="number" name="houseNumber" class="form-control" id="HouseNumber" placeholder="House Number" required>
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
                                                <label class="custom-control-label" for="GenderM">1</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="GenderF" name="gender" value="0" required>
                                                <label class="custom-control-label" for="GenderF">2</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Do you have refference</h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-ref" data-selector="show-form-ref" id="RefYes" name="refer" value="1"  required>
                                                <label class="custom-control-label" for="RefYes">1</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-ref" data-selector="hide-form-ref" id="RefNo" name="refer" value="0" required>
                                                <label class="custom-control-label" for="RefNo">2</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Inline Custom Radios</h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked" required>
                                                <label class="custom-control-label" for="customControlValidation2">1</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked" required>
                                                <label class="custom-control-label" for="customControlValidation3">2</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="card-title ml-3 referee-info">Referee Info</h4>
                        <hr class="hide">
                        <div class="form-row" id="Referee-card1">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Names">Names</label>
                                    <input type="text" name="names" class="form-control" id="Names" placeholder="Names">
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="IdentityReferee">Identity</label>
                                    <input type="number" class="form-control" id="IdentityReferee" placeholder="identity " name="identityReferee">
                                    <div class="invalid-tooltip">
                                        Please provide a valid state.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" id="Referee-card2">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                <label for="RefereeNumber">Referee Number</label>
                                <input type="text"  class="form-control" id="RefereeNumber" placeholder="Referee Number" >
                                <div class="invalid-tooltip">
                                    Please provide a valid state.
                                </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Relationship">Relationship</label>
                                    <input type="text" name="relationship" class="form-control" id="Relationship" placeholder="Relationship" >
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <!-- other info -->
                        <div class="form-row" id="Other-info">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Location">Highest Education Level</label>
                                    <select class="custom-select" name="location" id="Location" required>
                                        <option value="">Select Educational Level</option>
                                        <option value="1">one</option>
                                    </select>
                                    <div class="invalid-tooltip">You must select valid Location</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Location">Location</label>
                                    <select class="custom-select" name="location" id="Location" required>
                                        <option value="">Choose Location</option>
                                        <option value="1">one</option>
                                    </select>
                                    <div class="invalid-tooltip">You must select valid Location</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Location">Location</label>
                                    <select class="custom-select" name="location" id="Location" required>
                                        <option value="">Choose Location</option>
                                        <option value="1">one</option>
                                    </select>
                                    <div class="invalid-tooltip">You must select valid Location</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name</label>
                                    <input type="text" name="villageName" class="form-control" id="VillageName" placeholder="Village Name" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
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


$(function() {
        $(".referee-info").hide();
        $("#Referee-card1").hide();
        $("#Referee-card2").hide();
        $(".hide").hide();

        $('.change-ref').click(function() {
        var button = $(this).data('selector');
        console.log('text');
        if (button=='show-form-ref') {
            $(".referee-info").show(1000);
            $("#Referee-card1").show(1000);
            $("#Referee-card2").show(1000);
            $(".hide").show(1000);
        }
        else if (button=='hide-form-ref') {
            $(".referee-info").hide(1000);
            $("#Referee-card1").hide(1000);
            $("#Referee-card2").hide(1000);
            $(".hide").hide(1000);
        }
      })

  })
</script>
@endsection