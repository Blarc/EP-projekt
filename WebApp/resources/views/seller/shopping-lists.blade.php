@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Shopping lists') }}</div>

                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    <h3><a href="/item-show/{{$sl->id}}">{{$sl->name}}</a></h3>
                                    <small>added {{$sl->created_at}}</small>
                                    @if($sl->status == 0)
                                        <a href="/seller/sl/{{$sl->id}}/delete" class="btn btn-danger" style="float: right">Delete</a>
{{--                                        <a href="/seller/sl/{{$sl->id}}/accept" class="btn btn-dark" style="float: right">Accept</a>--}}
                                    @else
                                    @endif
                                    <a href="/item/{{$sl->id}}/edit" class="btn btn-dark" style="float: right">Edit</a>
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
