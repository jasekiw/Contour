<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/11/2016
 * Time: 11:54 AM
 */

?>

@extends('layouts.default')


@section('content')



    {!! Form::open(['method' => 'POST', 'url' => route('sheet.store'), 'class' => 'resource_form'] )!!}
    <div class="row">
        <div class="col-md-3">
            {!! Form::hidden('parent', $parentID) !!}
            {!! Form::hidden('type', 'default') !!}
            {!! Form::label('Name') !!}
            {!! Form::input('text', 'name', "") !!}
        </div>
        <div class="col-md-9">
            {!! Form::label('Template') !!}
            {!! Form::select('template', $templates) !!}
        </div>

    </div>
    <div class="row">
        {!! Form::submit('create') !!}
    </div>
    {!! Form::close() !!}

    <hr>
    {{--<h2><a href="{!! route("sheet.createfacility", ['id' => $parentID]) !!}">Or add new Facility</a></h2>--}}


@endsection
