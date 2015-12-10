<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 9:48 AM
 */
?>


@extends('layouts.login')


@section('content')


    {!!  Form::open(array('url' => 'login', 'id' => 'login', 'class' => 'form-horizontal', 'role' => 'form')) !!}


    <!-- if there are login errors, show them here -->
    <p>
        {{ $errors->first('username') }}
        {{ $errors->first('password') }}
    </p>

    <p class="title">Login with your username and password</p>


    <div class="form-group">


            <div class="col-sm-12">
                <div class="input-group">
                    {!! Form::text('username', Input::old('username'), array('placeholder' => 'username', 'class' => 'form-control')) !!}
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                </div>
            </div>

    </div>
    <div class="form-group">


            <div class="col-sm-12">
                <div class="input-group">
                    {!! Form::password('password', array('placeholder' => 'password', 'class' => 'form-control')) !!}
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                </div>
            </div>

    </div>
    <p>{!! Form::button('<i class="fa fa-arrow-circle-o-right"></i>Log In', array('type' => 'submit', 'class' => 'btn btn-custom-primary btn-lg btn-block btn-auth')) !!}</p>
    {!!Form::close()!!}

@endsection