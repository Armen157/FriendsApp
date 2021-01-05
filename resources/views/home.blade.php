@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="users-search-block">
                <p class="section-title">Find friends</p>
                <form action="#" method="post" class="users-search__form">
                    @csrf
                    <div class="form-group">
                        <input type="email" class="form-control" id="friend-search" name="find_friends" placeholder="Find friends...">
                    </div>
                </form>
                <div class="users-search__search-result">
                    <ul class="users-list">
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="my-friends-block">
                <p class="section-title">My friends</p>
                <ul class="users-list">

                    @if (count($friends) > 0)
                        @foreach($friends AS $friend)
                            @if($friend->status_id == 1)
                                <li class="users-list__item">
                                    <div class="users-list__item-inner df justify-content-between">
                                        <span class="users-list__name">{{$friend->name}} {{$friend->lastname}}</span>
                                        <button type="button" class="btn btn-primary add-friend-btn {{$friend->user_receiver_id}}"><i class="fas fa-user-minus"></i> Unfriend</button>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <li class='tc'>You don't have friends yet.</li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>

@include('blocks.error-modal')
@include('blocks.success-modal')
@endsection
