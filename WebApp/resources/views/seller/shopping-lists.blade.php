@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Unprocessed shopping lists') }}</div>

                    <div class="card-body">
                        @if(count($shoppingLists) > 1)
                            @foreach($shoppingLists as $sl)
                                <div class="well">
                                    @if($sl->status == 0)
                                        <h3><a href="/seller/sl-show/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
                                        <a href="/seller/sl/{{$sl->id}}/delete" class="btn btn-danger" style="float: right">Delete</a>
                                        <a href="/seller/sl/{{$sl->id}}/accept" class="btn btn-dark" style="float: right">Accept</a>
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
                                        <h3><a href="/seller/sl-show/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
                                        <a href="/seller/sl/{{$sl->id}}/stornate" class="btn btn-warning" style="float: right">Stornate</a>
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
                                        <h3><a href="/seller/sl-show/{{$sl->id}}">{{$sl->name}}</a></h3>
                                        <small>added {{$sl->created_at}}</small><br>
                                        <small>updated {{$sl->updated_at}}</small>
                                        <a href="/seller/sl/{{$sl->id}}/delete" class="btn btn-danger" style="float: right">Delete</a>
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
