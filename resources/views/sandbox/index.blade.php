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

   <style type="text/css">

      .dynamic_editor {
         margin: 20px 0;
      }
      .dynamic_editor input, form input{
         display:block;
         margin: 10px 0;
      }
      .dynamic_editor_result {
         border: 1px solid #666;
         min-height:100px;
      }
      #result {
         width:100%;
         max-width:100%;
         height:200px;
         display:block;

      }
   </style>

   {!!  Form::open(["method" => "POST", "route" => "api_getValue", "id" => "dynamic_submission"] ) !!}

   {!!Form::submit("Send Request")!!}
   {!!Form::close()!!}

   <div class="dynamic_editor">
      <label>Name</label>
      <input type="text" name="name" />
      <label>Value</label>
      <input type="text" name="value" />
      <input type="submit" name="add_attribute" value="Add">
      <hr>
      <input type="text" name="request" />
      <input type="submit" name="change_request" value="Change Request URL" />
   </div>
   <div class="dynamic_editor_result">
      <textarea id="result"></textarea>
   </div>
   <div class="error_output">

   </div>






@endsection
@section('scripts')
   @include('sandbox.scripts')
@endsection

