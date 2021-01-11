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
                    <form class="needs-validation" method="POST" action="{{ route('CreateItem') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="solarPanelType">Solar Panel Type</label>
                                    <select class="custom-select" name="solarPanelType" id="solarPanelType" required>
                                        <option value="">Choose type of solar panel</option>
                                        @foreach($SolarTypes as $singleType)
                                        <option value="{{ ($singleType->id) }}">{{ ($singleType->solarTypeName) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">You must select valid solar type</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="administrativeLocation">Administrative Location</label>
                                    <select class="custom-select" name="location" id="administrativeLocation" required>
                                        <option value="">Choose Administrative location</option>
                                        @foreach($Locations as $location)
                                        <option value="{{ ($location->id) }}">{{ ($location->locationName) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-tooltip">You must select a valid location</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="NumberOfSolar">How Many</label>
                                    <input type="number" name="numberOfSolar" class="form-control" id="NumberOfSolar"
                                        placeholder="Number of solar panel" onkeyup="getPrice()" required>
                                    <div class="invalid-tooltip">
                                        This field shouldn't be empty
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="TotalPrice">Total Price</label>
                                    <input type="text" disabled class="form-control" id="TotalPrice" placeholder="0"
                                        required>
                                    <div class="invalid-tooltip">
                                        Please provide a valid state.
                                    </div>
                                </div>
                                <input type="hidden" id="Price" name="price" value="0">

                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php
  $all= array();
  $qry=DB::table('solar_panel_types')
            ->get();
  foreach ($qry as $solar) {
    $all_type=array();
    $all_type['id']=$solar->id;
    $all_type['solarTypeName']=$solar->solarTypeName;
    $all_type['price']=$solar->price;
    array_push($all, $all_type);
  }
?>
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

var all = '<?php echo json_encode($all);?>';

function getPrice() {
    var sptype = $('#solarPanelType').val();
    var number = $('#NumberOfSolar').val();
    console.log(sptype + ' ' + number);
    var response = JSON.parse(all);
    $.each(response, function(item, value) {
        if (sptype == this.id) {

            // var amount=installment*this.price;
            // var total =(this.price*amountpaid)/installment;
            // console.log(total);
            // $('#pricetosave').val(total);
            $('#Price').val(this.price * number);
            $('#TotalPrice').val(this.price * number);
            // $('#totalPrice').val(this.price);
            // $('#totalPricedb').val(this.price);
        }
    })
}
</script>
@endsection