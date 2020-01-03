@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ADMIN Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <ul class="list-group">
                        @foreach(Auth::user()->sellers as $seller)
                            <li class="list-group-item">
                                {{ $seller->firstName }}
                                {{ $seller->lastName }}
                                <a style='float: right;' href='{{ route('manageSeller.submit', $seller->id) }}' type="button" class="btn btn-primary btn-sm">Edit seller profile</a>
                                <a style='float: right;' href='{{ route('manageSeller.submit', $seller->id) }}' type="button" class="btn btn-danger btn-sm">Deactivate seller</a>
                                <a style='float: right;' href='{{ route('manageSeller.submit', $seller->id) }}' type="button" class="btn btn-success btn-sm">Activate seller</a>
                            </li>
                        @endforeach 
                    </ul>
                    <a type="button" href='{{ route('createSeller') }}' class="btn btn-primary">Create new seller</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
