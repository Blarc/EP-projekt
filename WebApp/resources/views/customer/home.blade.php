@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Your Baskets') }}
                        <a style='float: right; margin-left: 1ex' href='/edit-profile' class="btn btn-primary">
                            {{ __('Edit profile') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 3)
                                        <h3><a href="/shop/shoppingLists/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
                                        {{--                                        <a href="/seller/sl/{{$sl->id}}/delete" class="btn btn-danger" style="float: right">Delete</a>--}}
                                        <a href="/shop/sl/{{$sl->id}}/checkout" class="btn btn-dark" style="float: right">Checkout</a>
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No shopping lists found</p>
                        @endif
                    </div>

                    <div class="card-header">{{ __('Submitted shopping lists') }}</div>

                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 0)
                                        <h3><a href="/shop/shoppingLists/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
{{--                                        <a href="/seller/sl/{{$sl->id}}/stornate" class="btn btn-warning" style="float: right">Stornate</a>--}}
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No shopping lists found</p>
                        @endif
                    </div>

                    <div class="card-header">{{ __('Processed shopping lists') }}</div>

                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 1)
                                        <h3><a href="/shop/shoppingLists/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
{{--                                        <a href="/seller/sl/{{$sl->id}}/stornate" class="btn btn-warning" style="float: right">Stornate</a>--}}
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No shopping lists found</p>
                        @endif
                    </div>

                    <div class="card-header">{{ __('Stornated shopping lists') }}</div>

                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 2)
                                        <h3><a href="/shop/shoppingLists/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
{{--                                        <a href="/seller/sl/{{$sl->id}}/delete" class="btn btn-danger" style="float: right">Delete</a>--}}
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
