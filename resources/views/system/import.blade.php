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
                    <form class="needs-validation" method="POST" action="{{ route('import') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary" for='photo' type="button">file</button>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="photo">
                                            <label class="custom-file-label" for="photo">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary ml-3" type="submit">import</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('script')

@endsection