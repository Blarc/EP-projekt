@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Items') }}
                        <a style='float: right; margin-left: 1ex' href='{{route('createItem')}}' class="btn btn-primary">
                            {{ __('Create new item') }}
                        </a>
                        <a style='float: right; margin-left: 1ex' href='/item-deactivated' class="btn btn-primary">Deactivated Items</a>

                    </div>

                    <div class="card-body">
                        @if(count($items) > 0)
                            @foreach($items as $item)
                                    <div class="well">
                                        <h3><a href="/item-show/{{$item->id}}">{{$item->name}}</a></h3>
                                        <h4>{{$item->price}} €</h4>
                                        <small>added {{$item->created_at}}</small><br>
                                        <small>updated {{$item->updated_at}}</small>
                                        <a href="/seller/item/{{$item->id}}/edit" class="btn btn-dark" style="float: right">Edit</a>
                                        <a href="/seller/item/{{$item->id}}/delete" class="btn btn-danger" style="float: right">Deactivate</a>
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
