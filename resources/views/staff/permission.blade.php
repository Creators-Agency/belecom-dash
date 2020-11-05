@extends('layouts/wrapper') @section('content')
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
                    <div class="form-row">
                        @foreach($permissions as $permission)
                        <div class="form-group col-md-3">
                            <form method="Post" action="{{ route($permission['permission'].'Perm') }}">
                                @csrf
                                <h4 class="ml-2">{{ ($permission['permission'])}}</h4>
                                <div class="col-sm-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="create"
                                            id="{{ ($permission['permission'].'Create') }}"
                                            {{ $permission['create'] == 1 ? 'checked': ''}} />
                                        <label class="custom-control-label"
                                            for="{{ ($permission['permission'].'Create') }}">Create</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="read"
                                            id="{{ ($permission['permission'].'Read') }}"
                                            {{ $permission['read'] == 1 ? 'checked': ''}} />
                                        <label class="custom-control-label"
                                            for="{{ ($permission['permission'].'Read') }}">Read</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="update"
                                            id="{{ ($permission['permission'].'Update') }}"
                                            {{ $permission['update'] == 1 ? 'checked': ''}} />
                                        <label class="custom-control-label"
                                            for="{{ ($permission['permission'].'Update') }}">Update</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="delete"
                                            id="{{ ($permission['permission'].'Delete') }}"
                                            {{ $permission['delete'] == 1 ? 'checked': ''}} />
                                        <label class="custom-control-label"
                                            for="{{ ($permission['permission'].'Delete') }}">Delete</label>
                                    </div>
                                    <input type="hidden" name="user" value="{{($staff->id)}}">
                                    <input type="hidden" name="permID" value="{{($permission['permissionID'])}}">
                                </div>
                                <button class="btn btn-primary m-2" type="submit">Update</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
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
            var validation = Array.prototype.filter.call(forms, function(
                form
            ) {
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
            });
        },
        false
    );
})();
</script>
@endsection