<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/12/2015
 * Time: 10:19 AM
 */

?>

@extends('layouts.default')

@section('style')
    @include('user.style')
@endsection
@section('content')


    <form action="{{ url('user/upload')}}" class="dropzone" id="profile_pic_dropzone"></form>

    <!-- NAV TABS -->
    <ul class="nav nav-tabs nav-tabs-custom-colored tabs-iconized">
        <li class="active"><a href="#profile-tab" data-toggle="tab"><i class="fa fa-user"></i> Profile</a></li>
        <li><a href="#activity-tab" data-toggle="tab"><i class="fa fa-rss"></i> Recent Activity</a></li>
        <li><a href="#settings-tab" data-toggle="tab"><i class="fa fa-gear"></i> Settings</a></li>
    </ul>
    <!-- END NAV TABS -->
    <div class="tab-content profile-page">
        <!-- PROFILE TAB CONTENT -->
        <div class="tab-pane profile active" id="profile-tab">
            <div class="row">
                <div class="col-md-3">
                    <div class="user-info-left profile_pic_container">

                        <img src="{!! $user_pic !!}" alt="Profile Picture" id="profile_picture"/>
                        {!! UserInterface::mini_drop_zone('profile') !!}
                        <h2>
                            {!! \UserInterface::editable('first_name',route('save_profile'), $first_name,'text', 'Enter Your First Name') !!}
                            {!!\UserInterface::editable('last_name',route('save_profile'), $last_name,'text', 'Enter Your Last Name') !!}
                            <i class="fa fa-circle green-font online-icon"></i><sup class="sr-only">online</sup>
                        </h2>
                        <div class="contact">
                            <a href="#" class="btn btn-block btn-custom-primary"><i class="fa fa-envelope-o"></i> Send
                                Message</a>
                            <a href="#" class="btn btn-block btn-custom-secondary"><i class="fa fa-book"></i> Add To
                                Contact</a>
                            <ul class="list-inline social">
                                {!! \UserInterface::editable('facebook_url', route('save_profile'),'<i class="fa fa-facebook-square"></i>' ,'text', 'Enter the url of your ') !!}
                                {{--<li><a href="#" title="Facebook"><i class="fa fa-facebook-square"></i></a></li>--}}
                                <li><a href="#" title="Twitter"><i class="fa fa-twitter-square"></i></a></li>
                                <li><a href="#" title="Google Plus"><i class="fa fa-google-plus-square"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="user-info-right">
                        <div class="basic-info">
                            <h3><i class="fa fa-square"></i> Basic Information</h3>
                            <p class="data-row">
                                <span class="data-name">Username</span>
                                <span class="data-value">{{$username}}</span>
                            </p>
                            <p class="data-row">
                                <span class="data-name">Birth Date</span>
                                {!! \UserInterface::editable('birthday',route('save_profile'), $birthday,'date', 'Enter Birthday') !!}
                            </p>
                            <p class="data-row">
                                <span class="data-name">Gender</span>
                                {!! \UserInterface::editable('gender',route('save_profile'), $gender,'text', 'Enter Gender') !!}
                            </p>
                            <p class="data-row">
                                <span class="data-name">Last Login</span>
                                <span class="data-value">{{$last_logged_in}}</span>
                            </p>
                            <p class="data-row">
                                <span class="data-name">Date Joined</span>
                                <span class="data-value">{{$date_joined}}</span>
                            </p>
                        </div>
                        <div class="contact_info">
                            <h3><i class="fa fa-square"></i> Contact Information</h3>
                            <p class="data-row">
                                <span class="data-name">Email</span>
                                {{--<span class="data-value">{{$email}}</span>--}}
                                {!! \UserInterface::editable('email',route('save_profile'), $email,'text', 'Enter Email Address') !!}
                            </p>
                            <p class="data-row">
                                <span class="data-name">Phone</span>
                                {!! \UserInterface::editable('phone',route('save_profile'), $phone,'text', 'Enter Phone Number') !!}
                            </p>
                            <p class="data-row">
                                <span class="data-name">Company</span>
                                {!! \UserInterface::editable('company',route('save_profile'), $company,'text', 'Enter the company you belong to.') !!}
                            </p>
                        </div>
                        <div class="about">
                            <h3><i class="fa fa-square"></i> About Me</h3>
                            {!! \UserInterface::editable('about',route('save_profile'), $about,'textarea', 'Enter information about you.') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PROFILE TAB CONTENT -->

        <!-- ACTIVITY TAB CONTENT -->
        <div class="tab-pane activity" id="activity-tab">
            <ul class="list-unstyled activity-list">
                <li>
                    <i class="fa fa-shopping-cart activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> commented on <a href="#">Special Deal 2013</a> <span class="timestamp">12 minutes ago</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-pencil activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> posted <a href="#">a new blog post</a> <span class="timestamp">4 hours ago</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-user activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> edited his profile <span class="timestamp">11 hours ago</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-pencil activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> has added review on <a href="#">jQuery Complete Guide</a> book <span
                            class="timestamp">Yesterday</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-thumbs-up activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> liked <a href="#">a post</a> <span class="timestamp">December 12</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-tasks activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> has completed one task <span class="timestamp">December 11</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-picture-o activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> uploaded <a href="#">new photos</a> <span
                            class="timestamp">December 5</span>
                    </p>
                </li>
                <li>
                    <i class="fa fa-credit-card activity-icon pull-left"></i>
                    <p>
                        <a href="#">Jonathan</a> has updated his credit card info <span
                            class="timestamp">September 28</span>
                    </p>
                </li>
            </ul>
            <p class="text-center more"><a href="#" class="btn btn-custom-primary">View more <i
                        class="fa fa-long-arrow-right"></i></a></p>
        </div>
        <!-- END ACTIVITY TAB CONTENT -->

        <!-- SETTINGS TAB CONTENT -->
        <div class="tab-pane settings" id="settings-tab">
            <form class="form-horizontal" role="form">
                <fieldset>
                    <h3><i class="fa fa-square"></i> Change Password</h3>
                    <div class="form-group">
                        <label for="old-password" class="col-sm-3 control-label">Old Password</label>
                        <div class="col-sm-4">
                            <input type="password" id="old-password" name="old-password" class="form-control">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-4">
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password2" class="col-sm-3 control-label">Repeat Password</label>
                        <div class="col-sm-4">
                            <input type="password" id="password2" name="password2" class="form-control">
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <h3><i class="fa fa-square"></i> Privacy</h3>
                    <label class="fancy-checkbox">
                        <input type="checkbox">
                        <span>Show my display name</span>
                    </label>
                    <label class="fancy-checkbox">
                        <input type="checkbox">
                        <span>Show my birth date</span>
                    </label>
                    <label class="fancy-checkbox">
                        <input type="checkbox">
                        <span>Show my email</span>
                    </label>
                    <label class="fancy-checkbox">
                        <input type="checkbox">
                        <span>Show my online status on chat</span>
                    </label>
                </fieldset>
                <h3><i class="fa fa-square"> </i>Notifications</h3>
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Receive message from administrator</label>
                        <ul class="col-sm-7 list-inline">
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-globe'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-globe'></i>">
                            </li>
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-phone'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-phone'></i>">
                            </li>
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-envelope'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-envelope'></i>">
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">New product has been added</label>
                        <ul class="col-sm-7 list-inline">
                            <li>
                                <input type="checkbox" class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-globe'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-globe'></i>">
                            </li>
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-phone'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-phone'></i>">
                            </li>
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-envelope'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-envelope'></i>">
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Product review has been approved</label>
                        <ul class="col-sm-7 list-inline">
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-globe'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-globe'></i>">
                            </li>
                            <li>
                                <input type="checkbox" class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-phone'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-phone'></i>">
                            </li>
                            <li>
                                <input type="checkbox" checked class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-envelope'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-envelope'></i>">
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Others liked your post</label>
                        <ul class="col-sm-7 list-inline">
                            <li>
                                <input type="checkbox" class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-globe'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-globe'></i>">
                            </li>
                            <li>
                                <input type="checkbox" class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-phone'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-phone'></i>">
                            </li>
                            <li>
                                <input type="checkbox" class="bs-switch switch-small" data-off="default"
                                       data-on-label="<i class='glyphicon glyphicon-envelope'></i>"
                                       data-off-label="<i class='glyphicon glyphicon-envelope'></i>">
                            </li>
                        </ul>
                    </div>
                </fieldset>
            </form>
            <p class="text-center"><a href="#" class="btn btn-custom-primary"><i class="fa fa-floppy-o"></i> Save
                    Changes</a></p>
        </div>
        <!-- END SETTINGS TAB CONTENT -->
    </div>


@endsection
@section('scripts')
    @include('user.scripts')
@endsection