<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/23/2015
 * Time: 9:59 AM
 */
use app\libraries\excel\formula\conversion\FormulaConversion;
use app\libraries\tags\DataTags;
?>


@extends('layouts.default')


@section('content')

   {!!  Form::open(["method" => "POST", "route" => "api_getValue", "id" => "dynamic_submission"] ) !!}

   {!!Form::submit("Send Request")!!}
   {!!Form::close()!!}

   <div class="dynamic_editor">
      <label>Name</label>
      <input type="text" name="name" />
      <label>Value</label>
      <input type="text" name="value" />
      <input type="submit" value="Add">
   </div>






@endsection
@section('scripts')
   @include('sandbox.scripts')
@endsection

