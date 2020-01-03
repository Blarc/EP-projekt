@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    CUSTOMER Dashboard
                    <a style='float: right;' href='/edit-profile' class="btn btn-primary">
                        {{ __('Edit profile') }}
                    </a>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as CUSTOMER!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
