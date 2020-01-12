@extends('layouts.app')

@section('content')
    @if(Auth::guest())
        <div class="jumbotron text-center">
            <h1>Welcome To E-Shopping!</h1>
            <p>
                <a class="btn btn-primary btn-lg" href="/login" role="button">Login</a>
                <a class="btn btn-success btn-lg" href="/register" role="button">Register</a>
            </p>
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Items</h3>
                    </div>

                    <div class="card-body">
                        @if(count($items) > 0)
                            @foreach($items as $item)
                                <div class="well">
                                    <span style='display: inline;'>
                                        <h3><a href="/shop/item-show/{{$item->id}}">{{$item->name}} {{$item->id}} </a> <div style='float: right;'>{{$item->price}} €</div></h3>
                                        <h4></h4>
                                    </span>
                                    <small>added {{$item->created_at}}</small><br>
                                    <small>updated {{$item->updated_at}}</small>
                                    @if(Auth::guest() || Auth::user()->role == 'customer')
                                        <a href="/shop/add/{{$item->id}}" class="btn btn-dark" style="float: right">Add to basket</a>
                                    @endif
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
