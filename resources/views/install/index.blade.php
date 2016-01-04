<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/28/2015
 * Time: 10:17 AM
 */
?>

@extends('layouts.install')
@section('content')
    <style type="text/css">
        .output {
            background:#002b36;
            color:white;
        }
        .message {
            text-align:center;
        }
    </style>
{!!  Form::open(array('url' => 'install', 'id' => 'install', 'class' => 'form-horizontal', 'role' => 'form')) !!}
    @if(isset($installedAlready))
        @if(!$installedAlready)
            <p class="title">Click the button to install</p>
            <p style="text-align:center">{!! Form::button('<i class="fa fa-arrow-circle-o-right"></i>Install', array('type' => 'submit', 'class' => 'btn btn-custom-primary btn-lg btn-block btn-auth')) !!}</p>
        @else
            <p style="text-align:center">The System is already installed</p>
        @endif
    @endif
    @if(isset($finished))
        <div class="output">
            {{$output}}
        </div>
        <p class="message">Installation succesful</p>
    @endif
{!!Form::close()!!}
@endsection