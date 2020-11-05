@if(count($errors) > 0)
@foreach($errors->all() as $error)
<div class="alert alert-dismissible fade show" role="alert"
    style="padding: 16px 8px; font-size: 12px; color: #fff; background: #ff8383; border-radius:  8px;">
    {{$error}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endforeach
@endif

@if(session('success'))
<div class="alert alert-dismissible fade show"
    style="padding: 16px 8px; font-size: 12px; color: #fff; background: #4db59d; border-radius:  8px;">
    {{session('success')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-dismissible fade show"
    style="padding: 16px 8px; font-size: 12px; color: #fff; background: #ff8383; border-radius:  8px;">
    {{session('error')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif