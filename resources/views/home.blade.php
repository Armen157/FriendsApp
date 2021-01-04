@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="users-search-block">
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
    </div>
</div>

@include('blocks.error-modal')
@include('blocks.success-modal')
@endsection
