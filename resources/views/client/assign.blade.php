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
                    <h4 class="card-title mb-5 pb-2">Product Distribution</h4>
                    <form class="needs-validation" method="POST" action="{{ route('assignClient') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="solarPanelType">Solar Panel Type</label>
                                    <select class="custom-select" name="solarPanelType" id="solarPanelType"
                                        onchange="getPrice()" required>
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
                                    <label for="TotalPrice">Loans Period</label>
                                    <input type="number" class="form-control" name="loansPeriod" placeholder="0"
                                        required>
                                    <div class="invalid-tooltip">
                                        Please provide a valid data.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="col-md-12 mb-3">
                                    <label for="PaidPrice">Paid Amount(s)</label>
                                    <input type="number" class="form-control" id="PaidPrice" name="paidPrice"
                                        placeholder="0 Frw" required>
                                    <div class="invalid-tooltip">
                                        Please provide a valid data.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="col-md-12 mb-3">
                                    <label for="TotalPrice">Total Price</label>
                                    <input type="text" disabled class="form-control" id="TotalPrice" placeholder="0"
                                        required>
                                    <div class="invalid-tooltip">
                                        Please provide a valid data.
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="clientIdentification" value="{{ ($client->identification) }}">
                            <input type="hidden" name="firstname" value="{{ ($client->firstname) }}">
                            <input type="hidden" name="lastname" value="{{ ($client->lastname) }}">
                            <input type="hidden" id="Price" name="price" value="0">
                        </div>
                        <div class="form-row">

                        </div>
                        <button class="btn  {{ ($btn == 1 ? 'btn-danger': 'btn-primary')}} "
                            {{ ($btn == 1 ? 'disabled': '')}} type="submit">Assign</button>
                        <i class="text-danger">{{ ($btn == 1 ? 'Stock is empty!': '')}}</i>
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
    console.log(sptype);
    var response = JSON.parse(all);
    $.each(response, function(item, value) {
        if (sptype == this.id) {

            // var amount=installment*this.price;
            // var total =(this.price*amountpaid)/installment;
            // console.log(total);
            // $('#pricetosave').val(total);
            $('#Price').val(this.price);
            $('#TotalPrice').val(this.price);
            // $('#totalPrice').val(this.price);
            // $('#totalPricedb').val(this.price);
        }
    })
}
</script>
@endsection