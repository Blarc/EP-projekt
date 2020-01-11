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
    <div class="row">
        @foreach ($items as $item)
        <div class="col-lg-2 card" >
            <div class="card-body text-center">
                <h4 class="card-title">{{$item->name}}</h4>
                <p class="card-text">{{$item->description}}</p>
                <b class="card-text">{{$item->price}}€</b>
                <br></br>
                <button class="btn btn-primary" style="margin:5px;">View more</button>
                <button class="btn btn-primary">Add to cart  <i class="fas fa-shopping-cart"></i></button>
            </div>
        </div>
        
        @endforeach
        </div>
@endsection