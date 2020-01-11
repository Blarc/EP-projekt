@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$item->name}}
                        <a style='float: right;' href='/shop' class="btn btn-primary">
                            {{ __('Back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <div>Name of item:  <strong>{{$item->name}}</strong></div>
                        <div>Price:  <strong>{{$item->price}}</strong></div>
                        <div>Description:  <strong>{{$item->description}}</strong></div>
                        <div>Date of last update:  <strong>{{$item->updated_at}}</strong></div>
                        <div><strong><h3>Price: {{$item->price}} €</h3></strong></div>
                        <div><a href="/shop/add/{{$item->id}}" class="btn btn-dark" style="float: right">Add to basket</a></div> {{--TODO spremeni gumb, da bo dodajal--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
