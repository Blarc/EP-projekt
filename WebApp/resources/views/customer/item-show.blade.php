@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$item->name}}</div>

                    <div class="card-body">
                        <div>Name of item:  <strong>{{$item->name}}</strong></div>
                        <div>Price:  <strong>{{$item->price}}</strong></div>
                        <div>Description:  <strong>{{$item->description}}</strong></div>
                        <div>Date of last update:  <strong>{{$item->updated_at}}</strong></div>
                        <div><a href="/seller/item/{{$item->id}}/edit" class="btn btn-dark" style="float: right">Add to basket</a></div> {{--TODO spremeni gumb, da bo dodajal--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
