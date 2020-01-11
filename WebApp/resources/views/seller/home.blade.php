@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    SELLER Dashboard
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

                    <ul class="list-group">
                        @foreach(Auth::user()->customers as $customer)
                            <li class="list-group-item">
                                {{ $customer->firstName }}
                                {{ $customer->lastName }}
                                <a href='{{route('manageCustomer', $customer->id)}}' style='float: right;'  type="button" class="btn btn-primary btn-sm">Edit customer profile</a>
                                @if($customer->active)
                                    <a style='float: right;' href='{{ route('changeProfileStatus', $customer->id) }}' type="button" class="btn btn-danger btn-sm">Deactivate customer</a>
                                @else
                                    <a style='float: right;' href='{{ route('changeProfileStatus', $customer->id) }}' type="button" class="btn btn-success btn-sm">Activate customer</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <a type="button" href='{{ route('createCustomer') }}' class="btn btn-primary">Create new customer</a>
                    <a type="button" href='{{ route('manageItems') }}' class="btn btn-primary">Create new item</a>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
