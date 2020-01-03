@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$sl->name}}</div>

                    <div class="card-body">
                        <div>Name of user:  <strong>{{$sl->user->firstName}} {{$sl->user->lastName}}</strong></div>
                        <div>Name of shopping list:  <strong>{{$sl->name}}</strong></div>
                        <div>Date of creation:  <strong>{{$sl->created_at}}</strong></div>
                        <div>Date of last update:  <strong>{{$sl->updated_at}}</strong></div>
                        <div>Id:  <strong>{{$sl->id}}</strong></div>
                        <div>User Id:  <strong>{{$sl->user_id}}</strong></div>
                        <div>Status:  <strong>{{$sl->status}}</strong></div>
{{--                        {{$sl -> items() -> pluck('name')}}--}}
                        <div class="card-header">Items</div>
                        <div class="card-body">
                            @if(count($sl -> items) > 0)
                                @foreach($sl -> items as $item)
                                    <div class="well">{{$item->name}}</div>
                                @endforeach
                            @else
                                <p>No items in shopping list</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
