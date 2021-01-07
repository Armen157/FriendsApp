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
                <div class="my-friends-block-inner">
                    <ul class="users-list">

                    </ul>
                </div>
        </div>
    </div>
</div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>

        var auth_user_id = {!! json_encode((int)auth()->id()) !!};

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('4b6b2339f454614d41b0', {
            cluster: 'ap2'
        });

        console.log("bbbb")
        console.log(auth_user_id)

        var channel = pusher.subscribe('my-channel');
        channel.bind('Friends', function(data) {

            if(auth_user_id == data['user_receiver_id']){
                $('#request-modal .request-sender').html(data['name'] +" "+ data['lastname']);
                $('#request-modal input[name="friendship_id"]').val(data['friendship_id']);
                $('#request-modal').modal();
            }

        });
    </script>
@include('blocks.error-modal')
@include('blocks.success-modal')
@include('blocks.request-modal')
@endsection
