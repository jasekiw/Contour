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
    <div class="upper-controls">
        <div class="right">
            <a href="{!! route('users_index') !!}">Cancel</a>
        </div>
    </div>
    {!! Form::open(['method' => 'PUT', 'url' => route('users_save', [$user->id]), 'class' => 'resource_form'] )!!}
    <div class="row">
        <div class="col-md-3">
            {!! Form::label('User Name') !!}
            {!! Form::input('text', 'username', $user->username) !!}
            {!! Form::label('Password') !!}
            {!! Form::input('password', 'password', '') !!}
            {!! Form::label('Email') !!}
            {!! Form::input('text', 'email', $user->email) !!}
        </div>
        <div class="col-md-9">
            {!! Form::label('User Level') !!}
            {!! Form::select('group', \app\libraries\ModelHelpers\UserAccessGroups::getAssociativeArray(), $user->user_access_group_id) !!}
        </div>

    </div>
    <div class="row">
        {!! Form::submit('save') !!}
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection