@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    ADMIN Dashboard
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
                        @foreach(Auth::user()->sellers as $seller)
                            <li class="list-group-item">
                                {{ $seller->firstName }}
                                {{ $seller->lastName }}
                                <a style='float: right;' href='{{ route('manageSeller.submit', $seller->id) }}' type="button" class="btn btn-primary btn-sm">Edit seller profile</a>
                                @if($seller->active)
                                    <a style='float: right;' href='{{ route('changeProfileStatus', $seller->id) }}' type="button" class="btn btn-danger btn-sm">Deactivate seller</a>
                                @else
                                <a style='float: right;' href='{{ route('changeProfileStatus', $seller->id) }}' type="button" class="btn btn-success btn-sm">Activate seller</a>
                                @endif
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
