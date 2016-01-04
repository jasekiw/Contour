<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:47 AM
 */
?>

@extends('layouts.default')


@section('content')

   <h1 class="heading">Welcome Back
   @if(isset($first_name))
      {{", " . $first_name}}
   @endif
   </h1>
   <hr class="black"/>
   <div class="row title_section">
      <div class="col-lg-6 col-sm-6 col-xs-12">
         <h3 class="updated_title">Recently Updated Reports</h3>
      </div>
      <div class="col-lg-6 col-sm-6 col-xs-12">

      </div>
   </div>

   <div class="row">
      <div class="col-lg-6 col-sm-6 col-xs-12 updated_items">


         @foreach($recentReports as $report)
            <div class="row updated_item same-height">
               <div class="col-lg-10 col-md-8 col-sm-12 col-xs-12 item_title">
                  <h2 class="title">{{$report["name"]}}</h2>
                  <h2 class="smaller updated">Updated {{$report["updated"]}} (EST)</h2>
               </div>
               <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 item_button">
                  <a href="{{$report["link"]}}" class="btn btn-info view_sheet" >View</a>
               </div>

            </div>
         @endforeach
      </div>
      <div class="col-lg-6 col-sm-6 col-xs-12 updated_items">

         @foreach($recentFacilities as $facility)
            <div class="row updated_item same-height">
               <div class="col-lg-10 col-md-8 col-sm-12 col-xs-12 item_title">
                  <h2 class="title">{{$facility["name"]}}</h2>
                  <h2 class="smaller updated">Updated {{$facility["updated"]}} (EST)</h2>
               </div>
               <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 item_button">
                  <a href="{{$facility["link"]}}" class="btn btn-info view_sheet" >View</a>
               </div>

            </div>
         @endforeach
      </div>
   </div>



@endsection

@section('scripts')
   @include('dashboard.script')
@endsection
