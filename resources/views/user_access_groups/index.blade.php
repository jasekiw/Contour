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
        There are {{sizeOf($groups)}} User Access Group(s)
    </div>
    <div class="users">
        @foreach($groups as $group)
            <a href="{!! route("user_access_groups_show", array( $group->id ) ) !!}" >{{$group->name}}</a>
        @endforeach
    </div>
    <div class="createNew">
        <a href="{!! route("user_access_groups_create") !!}">Create a new User Access Group</a>
    </div>
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection