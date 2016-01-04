<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/23/2015
 * Time: 3:53 AM
 */
    ?>

@extends('layouts.default')

@section('style')
    @include('user.style')
@endsection
@section('content')
    <style type="text/css">
        .users {
            margin:20px;
        }
        .users a {
            font-size: 25px;
            padding:10px;
        }
    </style>
    @if( session('message'))
        <div class="alert alert-success">
            <strong>{{ session('message') }}</strong>
        </div>
    @endif
    <div class="users_statistics">
        There are {{sizeOf($users)}} user(s)
    </div>
    <div class="users">
        @foreach($users as $user)
            <a href="{!! route("users_show", array( $user->id ) ) !!}" >{{$user->username}}</a>
        @endforeach
    </div>
    <div class="createNew">
        <a href="{!! route("users_create") !!}">Create a new user</a>
    </div>
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection