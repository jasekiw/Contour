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
        <div class="left">
            <a href="{!! route('user_access_groups_index') !!}">Cancel</a>
        </div>
        <div class="right">
            <a href="{!! route('user_access_groups_index') !!}">Cancel</a>
        </div>
    </div>
    {!! Form::open(['method' => 'PUT', 'url' => route('user_access_groups_save', ["id" => $group]), 'class' => 'resource_form'] )!!}
        <div class="inside">
            <div class="row border">
                <div class="col-md-3">
                    {!! Form::hidden('group', $group->id) !!}
                    {!! Form::label('Name') !!}
                    {!! Form::input('text', 'name', $group->name) !!}

                </div>
                <div class="col-md-9">
                    {!! Form::label('Menu') !!}
                    {!! Form::select('menu', $menus) !!}

                </div>

            </div>
        </div>
        <div class="row">
            {!! Form::submit('Save') !!}
        </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
    {{--@include('user.scripts')--}}
@endsection