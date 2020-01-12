@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>{{$item->name}}</h5>
                        <h6>{{$item->price}}€</h6>
                        <a style='float: right; margin-left: 1ex' href='/' class="btn btn-primary">
                            {{ __('Back') }}
                        </a>
                        
                    </div>

                    <div class="card-body">
                        <h4>Add item to a new basket</h4>
                        <form method="POST" action="/shoppingList-create/{{$item->id}}">
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
                        </form>
                        <hr>
                        <h4>Add item to an existing basket</h4>
                        @if(count($shoppingLists) > 0)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 3)
                                        <h3><a href="/shop/{{$sl->id}}/{{$item->id}}">{{$sl->name}}</a></h3>
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No shopping lists found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
