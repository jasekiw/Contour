<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/3/2015
 * Time: 2:29 PM
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
            <a href="{!! route('menu.index') !!}">Cancel</a>
        </div>
    </div>
    {!! Form::open(['method' => 'POST', 'url' => route('menu.store'), 'class' => 'resource_form'] )!!}
    <div class="row">
        <div class="col-md-3">
            {!! Form::label('Name') !!}
            {!! Form::input('text', 'name','') !!}

        </div>
        <div class="col-md-9">
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