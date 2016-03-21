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

    @if( session('message'))
        <div class="alert alert-success">
            <strong>{{ session('message') }}</strong>
        </div>
    @endif

    <div class="upper-controls">
        <div class="right">
            <a href="{!! route('user_access_groups_index') !!}">Cancel</a>
        </div>
    </div>
    {!! Form::open(['method' => 'POST', 'url' => route('user_access_groups_create'), 'class' => 'resource_form'] )!!}
    <div class="row">
        <div class="col-md-3">
            {!! Form::label('Name') !!}
            {!! Form::input('text', 'name','') !!}

        </div>
        <div class="col-md-9">
            {!! Form::label('Menu') !!}
            {!! Form::select('menu', $menus) !!}

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