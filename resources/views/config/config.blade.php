<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/12/2015
 * Time: 3:27 PM
 */
        ?>


@extends('layouts.default')

@section('style')
    @include('config.style')
@endsection
@section('content')

    <div class="widget">
        <div class="widget-header">
            <h3><i class="fa fa-edit"></i> Basic Input</h3>
        </div>
        <div class="widget-content">
            {{ Form::open(array('url' => route('save_congfig'), 'id' => 'configuration', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data')) }}
                <div class="form-group">
                    <label class="col-md-2 control-label">Company Name</label>
                    <div class="col-md-10">
                        {{Form::input('text', 'company_name', $company_name, array('class' => 'form-control'))}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Website Name</label>
                    <div class="col-md-10">
                        {{Form::input('text', 'website_name', $website_name, array('class' => 'form-control'))}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Website Description</label>
                    <div class="col-md-10">
                        {{Form::input('text', 'website_description', $website_description, array('class' => 'form-control'))}}
                    </div>
                </div>
            <div class="form-group">
                <label for="favicon" class="col-md-2 control-label">Fav Icon</label>
                <div class="col-md-10">
                    {{Form::file('favicon')}}
                    <img src="{{$favicon_url}}" />
                    {{--<input type="file" id="favicon">--}}
                    <p class="help-block"><em>Upload the favicon to be used</em></p>
                </div>
            </div>
            <div class="form-group">
                <label for="logo" class="col-md-2 control-label">Upload a Logo</label>
                <div class="col-md-10">
                    {{Form::file('logo')}}
                    <img src="{{$logo_url}}" />
                    {{--<input type="file" id="logo">--}}
                    <p class="help-block"><em>Upload the logo to be used</em></p>
                </div>
            </div>




            {{----}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Select</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<select class="form-control">--}}
                            {{--<option value="cheese">Cheese</option>--}}
                            {{--<option value="tomatoes">Tomatoes</option>--}}
                            {{--<option value="mozarella">Mozzarella</option>--}}
                            {{--<option value="mushrooms">Mushrooms</option>--}}
                            {{--<option value="pepperoni">Pepperoni</option>--}}
                            {{--<option value="onions">Onions</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Multiple</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<select multiple="" class="form-control">--}}
                            {{--<option value="cheese">Cheese</option>--}}
                            {{--<option value="tomatoes">Tomatoes</option>--}}
                            {{--<option value="mozarella">Mozzarella</option>--}}
                            {{--<option value="mushrooms">Mushrooms</option>--}}
                            {{--<option value="pepperoni">Pepperoni</option>--}}
                            {{--<option value="onions">Onions</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Checkbox</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<div class="checkbox">--}}
                            {{--<label>--}}
                                {{--<input type="checkbox" value="item1"> Item 1--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="checkbox">--}}
                            {{--<label>--}}
                                {{--<input type="checkbox" value="item2"> Item 2--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="checkbox">--}}
                            {{--<label>--}}
                                {{--<input type="checkbox" value="item3"> Item 3--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Inline</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<label class="checkbox-inline">--}}
                            {{--<input type="checkbox" value="item1"> Item 1--}}
                        {{--</label>--}}
                        {{--<label class="checkbox-inline">--}}
                            {{--<input type="checkbox" value="item2"> Item 2--}}
                        {{--</label>--}}
                        {{--<label class="checkbox-inline">--}}
                            {{--<input type="checkbox" value="item3"> Item 3--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Radio Button</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--<input type="radio" name="radio" value="radio1"> Radio Item 1--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--<input type="radio" name="radio" value="radio2"> Radio Item 2--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--<input type="radio" name="radio" value="radio3"> Radio Item 3--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-2 control-label">Inline</label>--}}
                    {{--<div class="col-md-10">--}}
                        {{--<label class="radio radio-inline">--}}
                            {{--<input type="radio" name="radioinline" value="radio1"> Radio Item 1--}}
                        {{--</label>--}}
                        {{--<label class="radio radio-inline">--}}
                            {{--<input type="radio" name="radioinline" value="radio2"> Radio Item 2--}}
                        {{--</label>--}}
                        {{--<label class="radio radio-inline">--}}
                            {{--<input type="radio" name="radioinline" value="radio3"> Radio Item 3--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group">--}}
                    {{--<label class="col-sm-2 control-label">Static Control</label>--}}
                    {{--<div class="col-sm-10">--}}
                        {{--<p class="form-control-static">email@example.com</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">

                    <div class="col-sm-10">
                        {{Form::button('Save', array('type' => 'submit', 'class' => 'btn btn-custom-primary btn-lg btn-auth', 'style' => 'float:right'))}}
                    </div>
                </div>
            {{Form::close();}}
        </div>
    </div>
@endsection

@section('scripts')
    @include('config.scripts')
@endsection
