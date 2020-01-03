@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Items') }}</div>

                    <div class="card-body">
                        @if(count($items) > 1)
                            @foreach($items as $item)
                                <div class="well">
                                    <h3><a href="/item-show/{{$item->id}}">{{$item->name}}</a></h3>
                                    <small>added {{$item->created_at}}</small>
                                    <a href="/item/{{$item->id}}/edit" class="btn btn-dark" style="float: right">Edit</a>
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
