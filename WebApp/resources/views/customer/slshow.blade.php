@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$sl->name}}
                        <a style='float: right;' href='/home' class="btn btn-primary">
                            {{ __('My shopping lists') }}
                        </a>
                        <a style='float: right; margin-right: 20px;' href='/' class="btn btn-primary">
                            {{ __('Back to shopping') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <div>Name of user:  <strong>{{$sl->user->firstName}} {{$sl->user->lastName}}</strong></div>
                        <div>Name of shopping list:  <strong>{{$sl->name}}</strong></div>
                        <div>Date of creation:  <strong>{{$sl->created_at}}</strong></div>
                        <div>Date of last update:  <strong>{{$sl->updated_at}}</strong></div>
                        <div>Id:  <strong>{{$sl->id}}</strong></div>
                        <div>Status:  <strong>{{$sl->status}}</strong></div>
{{--                        {{$sl -> items() -> pluck('name')}}--}}
                        <div class="card-header">Items</div>
                        <div class="card-body">
                            @if(count($sl -> items) > 0)
                                @foreach($sl -> items as $item)
                                    <div class="row">
                                        <div class="column well" style="float: left; width: 20%">{{$item->name}}</div>
                                        <div class="column well" style="float: left; width: 20%">{{$item->price}}€</div>
                                        <div class="column well" style="float: left; width: 20%">{{$item->pivot->items_amount}}x</div>
                                        <div class="column well" style="float: left; width: 40%">
                                            @if($sl->status == 3)
                                                <form method="POST" action="/shoppingList/amount/{{$sl->id}}/{{$item->id}}">
                                                    @csrf

                                                    <div class="form-group">
                                                        <label for="items_amount">amount</label>

                                                        <input id="items_amount" type="number" step="1" min="1" class="form-control @error('items_amount') is-invalid @enderror" name="items_amount" value="{{$item->pivot->items_amount}}" required autocomplete="items_amount" autofocus>

                                                        @error('items_amount')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                        <a href="/shop/delete/{{$sl->id}}/{{$item->id}}" class="btn btn-danger" style="float: right; margin-left: 0.5ex; margin-top: 0.5ex">Delete item</a>
                                                        <button type="submit" style="float: right; margin-top: 0.5ex" class="btn btn-primary">Calculate</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            @else
                                <p>No items in shopping list</p>
                            @endif
                        </div>
                        <div style="float: right"><h4>Total amount: {{$sl->totalAmount()}}€</h4></div><br><br>
                        @if(count($sl -> items) > 0)
                            @if($sl->status == 3)
                                <a href="/shop/sl/{{$sl->id}}/checkout" class="btn btn-dark" style="float: right">Checkout</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
