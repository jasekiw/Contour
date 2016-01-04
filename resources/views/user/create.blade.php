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
        .user{

            padding:10px;
        }
        .user  div{

            padding:10px 0;
        }
        .user_statistics {
            padding:20px;
        }
    </style>
    @if( session('message'))
        <div class="alert alert-success">
            <strong>{{ session('message') }}</strong>
        </div>
    @endif
    <div class="user_statistics">

        <a href="{!! route('users_index') !!}">Back to users</a>
    </div>
    <div class="user">
        {!! Form::open([ 'method' => 'POST', 'url' => route('users_create')]) !!}
        <div class="changeUsername">
            <label>Username</label>
            {!! Form::input('text', 'username') !!}
        </div>
        <div class="changePassword">
            <label>Password</label>
            {!! Form::input('text', 'password') !!}
        </div>
        <div class="email">
            <label>Email</label>
            {!! Form::input('text', 'email') !!}
        </div>
        <div clas="changeUserLevel">
            <label>User Access Level</label>
            <select name="group">
                @foreach($groups as $group)
                    <option value="{!! $group->id !!}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        {!!  Form::submit('Save', ['class' => 'btn btn-primary submit']) !!}
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection