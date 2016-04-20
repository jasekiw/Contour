<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/19/2016
 * Time: 1:42 PM
 */

?>
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <!-- logo -->
                    <div class="col-md-2 logo">
                        <a  href="{!! route('home') !!}"><img class="logo" src="{!! isset($logo_url) ? $logo_url : "" !!}" alt="Admin Dashboard" /></a>
                        <h1 class="sr-only">Evergreen - Admin Dashboard</h1>
                    </div>
                    <!-- end logo -->
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- search box -->
                                {{--<div id="tour-searchbox" class="input-group searchbox">--}}
                                {{--<input type="search" class="form-control" placeholder="enter keyword here...">--}}
                                {{--<span class="input-group-btn">--}}
                                {{--<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                        <!-- end search box -->
                            </div>
                            <div class="col-md-9">
                                <div class="top-bar-right">
                                    <!-- responsive menu bar icon -->
                                    <a href="#" class="hidden-md hidden-lg main-nav-toggle"><i class="fa fa-bars"></i></a>
                                    <!-- end responsive menu bar icon -->
                                    {{--<button type="button" id="global-volume" class="btn btn-link btn-global-volume"><i class="fa"></i></button>--}}
                                    <div class="notifications">
                                        <ul>
                                        </ul>
                                    </div>
                                    <!-- logged user and the menu -->
                                    <div class="logged-user">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                                @if(isset($user_pic))
                                                    <img class="avatar" src="{!! $user_pic !!}" alt="User Avatar" />
                                                @else
                                                    <i class="fa fa-user avatar"></i>
                                                @endif
                                                <span class="name">{{$username}}</span>
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="{{ route('view_profile') }}">
                                                        <i class="fa fa-user"></i>
                                                        <span class="text">Profile</span>
                                                    </a>
                                                </li>
                                                @if($user_access_group == "administrators")

                                                    <li>
                                                        <a href="{{ route('view_config') }}">
                                                            <i class="fa fa-cog"></i>
                                                            <span class="text">Settings</span>
                                                        </a>
                                                    </li>

                                                @endif

                                                @if($isAdmin)
                                                    <li>
                                                        <a href="{{ route('users_index') }}">
                                                            <i class="fa fa-users"></i>
                                                            <span class="text">Edit Users</span>
                                                        </a>
                                                    </li>

                                                @endif
                                                <li>
                                                    <a href="{{ route('logout') }}">
                                                        <i class="fa fa-power-off"></i>
                                                        <span class="text">Logout</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- end logged user and the menu -->
                                </div>
                                <!-- /top-bar-right -->
                            </div>
                        </div>
                        <!-- /row -->
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /top -->
