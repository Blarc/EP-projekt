@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    CUSTOMER Dashboard
                    <a style='float: right; margin-left: 1ex' href='/edit-profile' class="btn btn-primary">
                        {{ __('Edit profile') }}
                    </a>
                    <a style='float: right; margin-left: 1ex' href='/shop/baskets' class="btn btn-primary">
                        {{ __('Baskets') }}
                    </a>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(count($items) > 1)
                        @foreach($items as $item)
                            <div class="well">
                                <h3><a href="/shop/item-show/{{$item->id}}">{{$item->name}}</a></h3>
                                <h4>{{$item->price}} €</h4>
                                <small>added {{$item->created_at}}</small><br>
                                <small>updated {{$item->updated_at}}</small>
                                <a style="color: white; float: right" data-toggle="modal" data-target="#yourModal" class="btn btn-dark">Add to basket</a> {{--TODO spremeni gumb, da bo dodajal--}}
                                <div class="modal" tabindex="-1" role="dialog" id="yourModal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Select basket</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <form method="POST" action="{{ route('createShoppingList.post') }}">
                                                    @csrf

                                                    <div class="form-group row">
                                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                                            @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-6 offset-md-4">
                                                            <button type="submit" class="btn btn-primary">
                                                                {{ __('Create') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                        {{$items->links()}}
                    @else
                        <p>No items found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
