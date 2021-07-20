@extends('layouts/wrapper')
@section('meta')
<title>
    <?php
    try {
        echo $pageTitle;
    } catch (\Throwable $th) {
        echo 'Belecom';
    } ?>

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
                    <h4 class="card-title mb-5 pb-2">update Client</h4>
                    <form class="needs-validation" method="POST" action="{{ route('updatespecial') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="existing">existing</label>
                                    <input type="text" name="existing" class="form-control" id="existing" placeholder="exist" value="{{ old('firstName') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="col-md-12 mb-3">
                                    <label for="updated">updated</label>
                                    <input type="text" name="updated" class="form-control" id="updated" placeholder="updated" value="{{ old('firstName') }}" required>
                                    <div class="invalid-tooltip">This field shouldn't be empty</div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary ml-3" type="submit">update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- @section('script')
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
        $("#Other-info").hide();
        $("#Referee-card1").hide();
        $("#Referee-card2").hide();
        $(".hide").hide();

        $('.change-ref').click(function() {
            var button = $(this).data('selector');
            console.log('text');
            if (button == 'show-form-ref') {
                $(".referee-info").show(1000);
                $("#Referee-card1").show(1000);
                $("#Referee-card2").show(1000);
                $(".hide").show(1000);
            } else if (button == 'hide-form-ref') {
                $(".referee-info").hide(1000);
                $("#Referee-card1").hide(1000);
                $("#Referee-card2").hide(1000);
                $(".hide").hide(1000);
            }
        })
        $('.change-more-info').click(function() {
            var button = $(this).data('selector');
            if (button == 'show-form-info') {
                $("#Other-info").show(1000);
            } else if (button == 'hide-form-info') {
                $("#Other-info").hide(1000);
            }
        })

    })
</script>
@endsection -->