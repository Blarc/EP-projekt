@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    SELLER Dashboard
                    <a style='float: right;' href='/preferences' class="btn btn-primary">
                        {{ __('Preferences') }}
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="list-group">
                        @foreach(Auth::user()->customers as $customer)
                            <li class="list-group-item">
                                {{ $customer->firstName }}
                                {{ $customer->lastName }}
                                <a style='float: right;' href='{{ route('manageCustomer.submit', $customer->id) }}' type="button" class="btn btn-primary btn-sm">Edit customer profile</a>
                                <a style='float: right;' href='{{ route('manageCustomer.submit', $customer->id) }}' type="button" class="btn btn-danger btn-sm">Deactivate customer</a>
                                <a style='float: right;' href='{{ route('manageCustomer.submit', $customer->id) }}' type="button" class="btn btn-success btn-sm">Activate customer</a>
                            </li>
                        @endforeach 
                    </ul>
                    <a type="button" href='{{ route('createCustomer') }}' class="btn btn-primary">Create new customer</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
