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
                                <a href="/seller/item/{{$item->id}}/edit" class="btn btn-dark" style="float: right">Add to basket</a> {{--TODO spremeni gumb, da bo dodajal--}}
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
