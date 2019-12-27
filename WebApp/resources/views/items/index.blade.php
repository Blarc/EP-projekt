@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>Welcome To Laravel Page!</h1>
        <p>
            <a class="btn btn-primary btn-lg" href="/login" role="button">Login</a>
            <a class="btn btn-success btn-lg" href="/register" role="button">Register</a>
        </p>
    </div>
    <ul class="list-group">
        @foreach ($items as $item)
            <li class="list-group-item list-group-item-action flex-column align-items-start">{{$item->name}}</li>
        @endforeach
    </ul>
@endsection
