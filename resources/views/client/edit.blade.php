@extends('layouts/wrapper')
@section('meta')
<title>
    <?php 
try {
    echo $pageTitle;
} catch (\Throwable $th) {
    echo 'Belecom';
}?>

</title>
@endsection
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
                    <form class="needs-validation" method="POST" action='{{ route("UpdateClient") }}' novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="FirstName">First Name</label>
                                    <input type="text" name="firstName" class="form-control" id="FirstName"
                                        placeholder="First Name" value="{{ $client->firstname }}" required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="LastName">Last Name</label>
                                    <input type="text" name="lastName" class="form-control"
                                        value="{{ $client->lastname }}" id="FirstName" placeholder="Last Name"
                                        required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Identification">Identification</label>
                                    <input type="text" name="identification" class="form-control"
                                        value="{{ $client->identification }}" id="Identification"
                                        placeholder="Identification" required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="Age">DOB</label>
                                    <input type="date" name="age" class="form-control" id="Age"
                                        value="{{ $client->DOB }}" placeholder="Birth date" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="PrimaryNumber">Primary Number</label>
                                    <input type="number" name="primaryNumber" class="form-control"
                                        value="{{ $client->primaryPhone }}" id="PrimaryNumber"
                                        placeholder="Primary Number" required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="SecondaryNumber">Secondary Number</label>
                                    <input type="number" name="secondaryNumber" class="form-control"
                                        id="SecondaryNumber" placeholder="Secondary Number"
                                        value="{{ $client->secondaryPhone }}" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Location">Location</label>
                                    <select class="custom-select" name="location" id="Location" required>
                                        <?php 
                                    $loc = \App\Models\AdministrativeLocation::where('id',$client->location)->first();
                                    $alls = \App\Models\AdministrativeLocation::where('id','!=',$client->location)->get();
                                    ?>
                                        <option value="{{ $loc->id }}" selected>
                                            {{ $loc->locationName }}
                                        </option>
                                        @foreach($alls as $all)
                                        <option value="{{$all->id}}">{{ $all->locationName }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">
                                        You must select valid Location
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="VillageName">Village Name {{ $client->villageName }}</label>
                                    <input type="text" name="villageName" class="form-control"
                                        value="{{ $client->village }}" id="VillageName" placeholder="Village Name"
                                        required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="QuarterName">Quarter Name</label>
                                    <input type="text" value="{{ $client->quarterName }}" name="quarterName"
                                        class="form-control" id="QuarterName" placeholder="Quarter Name" required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="HouseNumber">House Number</label>
                                    <input type="number" value="{{ $client->houseNumber }}" name="houseNumber"
                                        class="form-control" id="HouseNumber" placeholder="House Number" required />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
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
                                                <input type="radio" class="custom-control-input" id="GenderM"
                                                    name="gender" value="1" required
                                                    {{ $client->gender == '1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="GenderM">Male</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="GenderF"
                                                    name="gender" value="0" required
                                                    {{ $client->gender == '0' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="GenderF">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            Do you have refference
                                        </h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-ref"
                                                    data-selector="show-form-ref" id="RefYes" name="refer" value="1"
                                                    required {{ $client->referredby != NULL ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="RefYes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-ref"
                                                    data-selector="hide-form-ref" id="RefNo" name="refer" value="0"
                                                    required {{ $client->referredby == NULL ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="RefNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            Any additional Info
                                        </h4>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-more-info"
                                                    data-selector="show-form-info" id="customControlValidation2"
                                                    name="additional-info" required
                                                    {{ $client->buildingMaterial != '' ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="customControlValidation2">Yes</label>
                                            </div>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input change-more-info"
                                                    data-selector="hide-form-info" id="customControlValidation3"
                                                    name="additional-info" required
                                                    {{ $client->buildingMaterial == '' ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="customControlValidation3">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="card-title ml-3 referee-info">
                            Referee Info
                        </h4>
                        <hr class="hide" />
                        <div class="form-row" id="Referee-card1">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Names">Names</label>
                                    <input type="text" name="names" class="form-control" id="Names"
                                        placeholder="Names" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="IdentityReferee">Identity</label>
                                    <input type="number" class="form-control" id="IdentityReferee"
                                        placeholder="identity " name="identityReferee" />
                                    <div class="invalid-tooltip">
                                        Please provide a valid state.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" id="Referee-card2">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="RefereeNumber">Referee phone</label>
                                    <input type="text" class="form-control" id="RefereeNumber"
                                        placeholder="Referee phone" name="refereeNumber" />
                                    <div class="invalid-tooltip">
                                        Please provide a valid.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="Relationship">Relationship</label>
                                    <input type="text" name="relationship" class="form-control" id="Relationship"
                                        placeholder="Relationship" />
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
                                    <label for="Education">Education Level</label>
                                    <select class="custom-select" name="education" id="Education">
                                        <option value="">
                                            Educational Level
                                        </option>
                                        <option value="1">None</option>
                                        <option value="2">Primary School</option>
                                        <option value="3">Vocational Training school</option>
                                        <option value="4">Secondary Diploma</option>
                                        <option value="5">College Degree</option>
                                        <option value="6">University Degree</option>
                                    </select>
                                    <div class="invalid-tooltip">
                                        You must select valid Location
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="EmploymentStatus">Employment Status</label>
                                    <select class="custom-select" name="employmentStatus" id="EmploymentStatus">
                                        <option value="">
                                            Choose Employment Status
                                        </option>
                                        <option value="">Choose Employment Status</option>
                                        <option value="1">Contractor</option>
                                        <option value="2">Part time</option>
                                        <option value="3">Self employed</option>
                                        <option value="4">Un-employed</option>
                                    </select>
                                    <div class="invalid-tooltip">
                                        You must select valid Location
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="sourceOfIncome">Source Of Income</label>
                                    <input type="text" name="sourceOfIncome" class="form-control" id="SourceOfIncome"
                                        placeholder="source Of Income" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="SourceOfEnergy">Source Of Energy</label>
                                    <select class="custom-select" name="sourceOfEnergy" id="SourceOfEnergy">
                                        <option value="">
                                            Source of energy
                                        </option>
                                        <option value="">Choose Of Energy</option>
                                        <option value="1">Solar Energy</option>
                                        <option value="2">Electric Energy</option>
                                        <option value="3">Biogas Energy</option>
                                        <option value="4">Wind Energy</option>

                                    </select>
                                    <div class="invalid-tooltip">
                                        You must select valid Location
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="NumberOfPeopleSchool">Number of Family Member</label>
                                    <input type="number" name="numberOfPeopleSchool" class="form-control"
                                        id="NumberOfPeopleSchool" placeholder="Number of Family Member" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="MemberInSchool">Member In School</label>
                                    <input type="number" name="memberInSchool" class="form-control" id="MemberInSchool"
                                        placeholder="Member In School" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="MinorF">Number of Female Under 17</label>
                                    <input type="number" name="minorF" class="form-control" id="MinorF"
                                        placeholder="Number of Female U/17" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="MajorF">Number of Female Above 18</label>
                                    <input type="text" name="majorF" class="form-control" id="MajorF"
                                        placeholder="Number of Female Above 18" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="MinorM">Number of Male Under 17</label>
                                    <input type="text" name="minorM" class="form-control" id="MinorM"
                                        placeholder="Number of male Under 17" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="MajorM">Number of Male above 17</label>
                                    <input type="text" name="majorM" class="form-control" id="MajorM"
                                        placeholder="Number of Male above 18" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <label for="RoofMaterial">Roof Material</label>
                                    <input type="text" name="roofMaterial" class="form-control" id="RoofMaterial"
                                        placeholder="Roof Material" />
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$client->id}}">
                        <button class="btn btn-primary ml-3" type="submit">
                            Update Client
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('script')
<script type="text/javascript">
(function() {
    "use strict";
    window.addEventListener(
        "load",
        function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(
                forms,
                function(form) {
                    form.addEventListener(
                        "submit",
                        function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add("was-validated");
                        },
                        false
                    );
                }
            );
        },
        false
    );
})();

$(function() {
    $(".referee-info").hide();
    $("#Other-info").hide();
    $("#Referee-card1").hide();
    $("#Referee-card2").hide();
    $(".hide").hide();

    $(".change-ref").click(function() {
        var button = $(this).data("selector");
        console.log("text");
        if (button == "show-form-ref") {
            $(".referee-info").show(1000);
            $("#Referee-card1").show(1000);
            $("#Referee-card2").show(1000);
            $(".hide").show(1000);
        } else if (button == "hide-form-ref") {
            $(".referee-info").hide(1000);
            $("#Referee-card1").hide(1000);
            $("#Referee-card2").hide(1000);
            $(".hide").hide(1000);
        }
    });
    $(".change-more-info").click(function() {
        var button = $(this).data("selector");
        if (button == "show-form-info") {
            $("#Other-info").show(1000);
        } else if (button == "hide-form-info") {
            $("#Other-info").hide(1000);
        }
    });
});
</script>
@endsection