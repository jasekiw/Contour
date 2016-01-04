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

        <a href="{!! route('user_access_groups_index') !!}">Back to User Access Groups</a>
    </div>
    <div class="user">
        {!! Form::open([ 'method' => 'POST', 'url' => route('user_access_groups_create')]) !!}
        <div class="changePassword">
            <label>Change Name</label>
            {!! Form::input('text', 'group') !!}
        </div>

        {!!  Form::submit('Save', ['class' => 'btn btn-primary submit']) !!}
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection