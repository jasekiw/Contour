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

    <div class="upper-controls">
        <div class="right">
            <a href="{!! route('users_index') !!}">Cancel</a>
        </div>
    </div>
    {!! Form::open(['method' => 'POST', 'url' => route('users.store'), 'class' => 'resource_form'] )!!}
    <div class="row">
        <div class="col-md-3">
            {!! Form::label('User Name') !!}
            {!! Form::input('text', 'username', "") !!}
            {!! Form::label('Password') !!}
            {!! Form::input('text', 'password', "") !!}
            {!! Form::label('Email') !!}
            {!! Form::input('text', 'email', "") !!}
        </div>
        <div class="col-md-9">
            {!! Form::label('User Level') !!}
            {!! Form::select('group', \app\libraries\ModelHelpers\UserAccessGroups::getAssociativeArray(), '1') !!}
        </div>

    </div>
    <div class="row">
        {!! Form::submit('create') !!}
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection