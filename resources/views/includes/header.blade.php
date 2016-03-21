<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:39 AM
 */
        ob_start();
?>

<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <head>
        <title>{!! isset($title) ? $title : "" !!} | Evergreen</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Evergreen - Financial Manager">
        <meta name="author" content="Jason Gallavin">
        <!-- CSS -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/my-custom-styles.css') }}" rel="stylesheet" type="text/css">

        <!--[if lte IE 9]>
        <link href="{{asset('assets/css/main-ie.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/css/main-ie-part2.css') }}" rel="stylesheet" type="text/css"/>
        <![endif]-->

        <!-- CSS for demo style switcher. you can remove this -->
       <!--<link href="demo-style-switcher/assets/css/style-switcher.css" rel="stylesheet" type="text/css">-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ isset($favicon_url) ? $favicon_url : "" }}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{isset($favicon_url) ? $favicon_url : "" }}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ isset($favicon_url) ? $favicon_url : ""}}">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{isset($favicon_url) ? $favicon_url : ""}}">
        <link href='{{asset('assets/fonts/open_sans/open_sans.css') }}' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="{{isset($favicon_url) ? $favicon_url : "" }}">
        {!!   Theme::header() !!}
        {!!  Theme::header($title) !!}
        @yield('style')
    </head>
        <?php
            ob_flush();
            flush();
        ?>
    <body class="dashboard @if(isset($class)) {!! $class !!} @endif">
    <!-- WRAPPER -->
    <div class="wrapper">
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
        <!-- BOTTOM: LEFT NAV AND RIGHT MAIN CONTENT -->
        <div class="bottom">
            <div class="container">
                <div class="row">
                    <!-- left sidebar -->
                    <div class="col-md-2 left-sidebar">
                        <!-- main-nav -->
                        <nav class="main-nav">
                            <ul class="main-menu">
                                <li class="active">
                                    <a href="#" class="js-sub-menu-toggle">
                                        <i class="fa fa-dashboard fa-fw"></i><span class="text">Dashboard</span>
                                        {{--<i class="toggle-icon fa fa-angle-down"></i>--}}
                                    </a>
                                    <ul class="sub-menu open">

                                    </ul>
                                </li>
<!--                                --><?php $main_menu = Contour::getThemeManager()->getMenuManager()->getAssociatedMenu()


                                ?>
                                @if(isset($main_menu))
                                    @foreach($main_menu->getMenuItems() as $menuItem)
                                        <li class=""><a href="{!! $menuItem->get_href() !!}"><span class="text">{{$menuItem->getName()}}</span></a></li>
                                    @endforeach
                                @endif

                            </ul>
                        </nav>
                        <!-- /main-nav -->
                        <div class="sidebar-minified js-toggle-minified">
                            <i class="fa fa-angle-left"></i>
                        </div>
                        <!-- sidebar content -->
                        <div class="sidebar-content">
                        </div>
                        <!-- end sidebar content -->
                    </div>
                    <!-- end left sidebar -->

                    <!-- end top general alert -->
                    <!-- content-wrapper -->
                    <div class="col-md-10 content-wrapper">
                        <div class="row">
                            <div class="col-md-4 ">
                                @if(isset($breadcrumbs))
                                    <ul class="breadcrumb">
                                        <li><i class="fa fa-home"></i><a href="#">Home</a></li>
                                        @foreach($breadcrumbs as $breadcrumb)
                                        <li class="active"><a href="{!! $breadcrumb['link'] !!}" >{{ $breadcrumb['title'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                <ul class="breadcrumb">
                                    <li><i class="fa fa-home"></i><a href="#">Home</a></li>
                                    <li class="active">{{$title }}</li>
                                </ul>
                                @endif
                            </div>
                            <div class="col-md-8 ">
                                <div class="top-content">
                                    <ul class="list-inline mini-stat">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- main -->
                        <div class="content">
                            <div class="main-header @if(isset($subtitle)) hasSubtitle @endif">
                                <h2>{{$title }}</h2>
                                @if(isset($subtitle))
                                    <em>{{$subtitle}}</em>
                                @endif
                            </div>

                            <div class="main-content">
