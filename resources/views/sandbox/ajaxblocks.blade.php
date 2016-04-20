<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/4/2015
 * Time: 4:00 PM
 */
use app\libraries\theme\userInterface\DataBlockEditor;
?>


@extends('layouts.default')


@section('content')

    <input name="test" datablock="1" type="text" />


@endsection

@section('scripts')
    @include('sandbox.scripts')
@endsection

