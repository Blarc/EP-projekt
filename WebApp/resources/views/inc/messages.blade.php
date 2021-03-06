@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success container card">
        {{session('success')}}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning container card">
        {{session('warning')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger container card">
        {{session('error')}}
    </div>
@endif