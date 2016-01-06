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
        <link rel="shortcut icon" href="{{isset($favicon_url) ? $favicon_url : "" }}">
        {!!   Theme::header() !!}
        {!!  Theme::header($title) !!}
        @yield('style')
    </head>
<?php
        ob_flush();
        flush();

        ?>
    <body class="dashboard">
    <!-- WRAPPER -->
    <div class="wrapper">
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <!-- logo -->
                    <div class="col-md-2 logo">
                        <a  href="{!! route('home') !!}"><img class="logo" src="{!! isset($logo_url) ? $logo_url : "" !!}" alt="KingAdmin - Admin Dashboard" /></a>
                        <h1 class="sr-only">Evergreen - Admin Dashboard</h1>
                    </div>
                    <!-- end logo -->
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- search box -->
                                <div id="tour-searchbox" class="input-group searchbox">
                                    <input type="search" class="form-control" placeholder="enter keyword here...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                </div>
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
                                            <!-- notification: inbox -->
                                            {{--<li class="notification-item inbox">--}}
                                                {{--<div class="btn-group">--}}
                                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                                                        {{--<i class="fa fa-envelope"></i><span class="count">2</span>--}}
                                                        {{--<span class="circle"></span>--}}
                                                    {{--</a>--}}
                                                    {{--<ul class="dropdown-menu" role="menu">--}}
                                                        {{--<li class="notification-header">--}}
                                                            {{--<em>You have 2 unread messages</em>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="inbox-item clearfix">--}}
                                                            {{--<a href="#">--}}
                                                                {{--<div class="media">--}}
                                                                    {{--<div class="media-left">--}}
                                                                        {{--<img class="media-object" src="{{ asset('assets/img/user1.png') }}" alt="Antonio">--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="media-body">--}}
                                                                        {{--<h5 class="media-heading name">Antonius</h5>--}}
                                                                        {{--<p class="text">The problem just happened this morning. I can't see ...</p>--}}
                                                                        {{--<span class="timestamp">4 minutes ago</span>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="inbox-item unread clearfix">--}}
                                                            {{--<a href="#">--}}
                                                                {{--<div class="media">--}}
                                                                    {{--<div class="media-left">--}}
                                                                        {{--<img class="media-object" src="{{ asset('assets/img/user2.png') }}" alt="Antonio">--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="media-body">--}}
                                                                        {{--<h5 class="media-heading name">Michael</h5>--}}
                                                                        {{--<p class="text">Hey dude, cool theme!</p>--}}
                                                                        {{--<span class="timestamp">2 hours ago</span>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="inbox-item unread clearfix">--}}
                                                            {{--<a href="#">--}}
                                                                {{--<div class="media">--}}
                                                                    {{--<div class="media-left">--}}
                                                                        {{--<img class="media-object" src="{{ asset('assets/img/user3.png') }}" alt="Antonio">--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="media-body">--}}
                                                                        {{--<h5 class="media-heading name">Stella</h5>--}}
                                                                        {{--<p class="text">Ok now I can see the status for each item. Thanks! :D</p>--}}
                                                                        {{--<span class="timestamp">Oct 6</span>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="inbox-item clearfix">--}}
                                                            {{--<a href="#">--}}
                                                                {{--<div class="media">--}}
                                                                    {{--<div class="media-left">--}}
                                                                        {{--<img class="media-object" src="{{ asset('assets/img/user4.png') }}" alt="Antonio">--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="media-body">--}}
                                                                        {{--<h5 class="media-heading name">Jane Doe</h5>--}}
                                                                        {{--<p class="text"><i class="fa fa-reply"></i> Please check the status of your ...</p>--}}
                                                                        {{--<span class="timestamp">Oct 2</span>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="inbox-item clearfix">--}}
                                                            {{--<a href="#">--}}
                                                                {{--<div class="media">--}}
                                                                    {{--<div class="media-left">--}}
                                                                        {{--<img class="media-object" src="{{ asset('assets/img/user5.png') }}" alt="Antonio">--}}
                                                                    {{--</div>--}}
                                                                    {{--<div class="media-body">--}}
                                                                        {{--<h5 class="media-heading name">John Simmons</h5>--}}
                                                                        {{--<p class="text"><i class="fa fa-reply"></i> I've fixed the problem :)</p>--}}
                                                                        {{--<span class="timestamp">Sep 12</span>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="notification-footer">--}}
                                                            {{--<a href="#">View All Messages</a>--}}
                                                        {{--</li>--}}
                                                    {{--</ul>--}}
                                                {{--</div>--}}
                                            {{--</li>--}}
                                            <!-- end notification: inbox -->
                                            <!-- notification: general -->
                                            {{--<li class="notification-item general">--}}
                                                {{--<div class="btn-group">--}}
                                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                                                        {{--<i class="fa fa-bell"></i><span class="count">8</span>--}}
                                                        {{--<span class="circle"></span>--}}
                                                    {{--</a>--}}
                                                    {{--<ul class="dropdown-menu" role="menu">--}}
                                                        {{--<li class="notification-header">--}}
                                                            {{--<em>You have 8 notifications</em>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-comment green-font"></i>--}}
                                                                {{--<span class="text">New comment on the blog post</span>--}}
                                                                {{--<span class="timestamp">1 minute ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-user green-font"></i>--}}
                                                                {{--<span class="text">New registered user</span>--}}
                                                                {{--<span class="timestamp">12 minutes ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-comment green-font"></i>--}}
                                                                {{--<span class="text">New comment on the blog post</span>--}}
                                                                {{--<span class="timestamp">18 minutes ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-shopping-cart red-font"></i>--}}
                                                                {{--<span class="text">4 new sales order</span>--}}
                                                                {{--<span class="timestamp">4 hours ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-edit yellow-font"></i>--}}
                                                                {{--<span class="text">3 product reviews awaiting moderation</span>--}}
                                                                {{--<span class="timestamp">1 day ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-comment green-font"></i>--}}
                                                                {{--<span class="text">New comment on the blog post</span>--}}
                                                                {{--<span class="timestamp">3 days ago</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-comment green-font"></i>--}}
                                                                {{--<span class="text">New comment on the blog post</span>--}}
                                                                {{--<span class="timestamp">Oct 15</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li>--}}
                                                            {{--<a href="#">--}}
                                                                {{--<i class="fa fa-warning red-font"></i>--}}
                                                                {{--<span class="text red-font">Low disk space!</span>--}}
                                                                {{--<span class="timestamp">Oct 11</span>--}}
                                                            {{--</a>--}}
                                                        {{--</li>--}}
                                                        {{--<li class="notification-footer">--}}
                                                            {{--<a href="#">View All Notifications</a>--}}
                                                        {{--</li>--}}
                                                    {{--</ul>--}}
                                                {{--</div>--}}
                                            {{--</li>--}}
                                            <!-- end notification: general -->
                                        </ul>
                                    </div>
                                    <!-- logged user and the menu -->
                                    <div class="logged-user">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                                <img src="{{ isset($user_pic) ?  $user_pic : "" }}" alt="User Avatar" />
                                                <span class="name">{{$username}}</span> <span class="caret"></span>
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
                                                            <i class="fa fa-user"></i>
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
                                        <i class="toggle-icon fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu open">
                                        {{--  --}}
                                        <?php
                                        $main_menu = Contour::getThemeManager()->getMenuManager()->get_menu("Main_Menu");
                                        ?>
                                        @if(isset($main_menu))
                                            @foreach(Contour::getThemeManager()->getMenuManager()->get_menu("Main_Menu")->getMenuItems() as $menuItem)
                                                <li class="active"><a href="{!! $menuItem->get_href() !!}"><span class="text">{{$menuItem->getName()}}</span></a></li>
                                            @endforeach
                                        @endif
                                        {{--<li class="active"><a href="{{ route('home') }}"><span class="text">Dashboard</span></a></li>--}}
                                        {{--<li><a href="{{route('import_excel')}}"><span class="text">Import</span></a></li>--}}
                                        {{--<li><a href="{{route('index_tags')}}"><span class="text">Tag Browser</span></a></li>--}}
                                    </ul>


                                </li>
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-clipboard fa-fw"></i><span class="text">Pages</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="page-profile.html"><span class="text">Profile</span></a></li>--}}
                                        {{--<li><a href="page-invoice.html"><span class="text">Invoice</span></a></li>--}}
                                        {{--<li><a href="page-knowledgebase.html"><span class="text">Knowledge Base</span></a></li>--}}
                                        {{--<li><a href="page-inbox.html"><span class="text">Inbox</span></a></li>--}}
                                        {{--<li><a href="page-new-message.html"><span class="text">New Message</span></a></li>--}}
                                        {{--<li><a href="page-view-message.html"><span class="text">View Message</span></a></li>--}}
                                        {{--<li><a href="page-search-result.html"><span class="text">Search Result</span></a></li>--}}
                                        {{--<li><a href="page-submit-ticket.html"><span class="text">Submit Ticket</span></a></li>--}}
                                        {{--<li><a href="page-file-manager.html"><span class="text">File Manager <span class="badge element-bg-color-blue">New</span></span></a></li>--}}
                                        {{--<li><a href="page-projects.html"><span class="text">Projects <span class="badge element-bg-color-blue">New</span></span></a></li>--}}
                                        {{--<li><a href="page-project-detail.html"><span class="text">Project Detail <span class="badge element-bg-color-blue">New</span></span></a></li>--}}
                                        {{--<li><a href="page-faq.html"><span class="text">FAQ <span class="badge element-bg-color-blue">New</span></span></a></li>--}}
                                        {{--<li><a href="page-register.html"><span class="text">Register</span></a></li>--}}
                                        {{--<li><a href="page-login.html"><span class="text">Login</span></a></li>--}}
                                        {{--<li><a href="page-404.html"><span class="text">404</span></a></li>--}}
                                        {{--<li><a href="page-blank.html"><span class="text">Blank Page</span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-bar-chart-o fw"></i><span class="text">Charts &amp; Statistics</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="charts-statistics.html"><span class="text">Charts</span></a></li>--}}
                                        {{--<li><a href="charts-statistics-interactive.html"><span class="text">Interactive Charts</span></a></li>--}}
                                        {{--<li><a href="charts-statistics-real-time.html"><span class="text">Realtime Charts</span></a></li>--}}
                                        {{--<li><a href="charts-d3charts.html"><span class="text">D3 Charts</span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-edit fw"></i><span class="text">Forms</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="form-inplace-editing.html"><span class="text">In-place Editing</span></a></li>--}}
                                        {{--<li><a href="form-elements.html"><span class="text">Form Elements</span></a></li>--}}
                                        {{--<li><a href="form-layouts.html"><span class="text">Form Layouts</span></a></li>--}}
                                        {{--<li><a href="form-bootstrap-elements.html"><span class="text">Bootstrap Elements</span></a></li>--}}
                                        {{--<li><a href="form-validations.html"><span class="text">Validation</span></a></li>--}}
                                        {{--<li><a href="form-file-upload.html"><span class="text">File Upload</span></a></li>--}}
                                        {{--<li><a href="form-text-editor.html"><span class="text">Text Editor</span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-list-alt fw"></i><span class="text">UI Elements</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="ui-elements-general.html"><span class="text">General Elements</span></a></li>--}}
                                        {{--<li><a href="ui-elements-tabs.html"><span class="text">Tabs</span></a></li>--}}
                                        {{--<li><a href="ui-elements-buttons.html"><span class="text">Buttons</span></a></li>--}}
                                        {{--<li><a href="ui-elements-icons.html"><span class="text">Icons <span class="badge element-bg-color-blue">Updated</span></span></a></li>--}}
                                        {{--<li><a href="ui-elements-flash-message.html"><span class="text">Flash Message</span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="widgets.html">--}}
                                        {{--<i class="fa fa-puzzle-piece fa-fw"></i><span class="text">Widgets <span class="badge element-bg-color-blue">Updated</span></span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-gears fw"></i><span class="text">Components</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="components-wizard.html"><span class="text">Wizard (with validation)</span></a></li>--}}
                                        {{--<li><a href="components-calendar.html"><span class="text">Calendar</span></a></li>--}}
                                        {{--<li><a href="components-maps.html"><span class="text">Maps</span></a></li>--}}
                                        {{--<li><a href="components-gallery.html"><span class="text">Gallery</span></a></li>--}}
                                        {{--<li><a href="components-tree-view.html"><span class="text">Tree View <span class="badge element-bg-color-blue">New</span></span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle">--}}
                                        {{--<i class="fa fa-table fw"></i><span class="text">Tables</span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu ">--}}
                                        {{--<li><a href="tables-static-table.html"><span class="text">Static Table</span></a></li>--}}
                                        {{--<li><a href="tables-dynamic-table.html"><span class="text">Dynamic Table</span></a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                                {{--<li><a href="typography.html"><i class="fa fa-font fa-fw"></i><span class="text">Typography</span></a></li>--}}
                                {{--<li>--}}
                                    {{--<a href="#" class="js-sub-menu-toggle"><i class="fa fa-bars"></i>--}}
                                        {{--<span class="text">MenuManager Lvl 1 <span class="badge element-bg-color-blue">New</span></span>--}}
                                        {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="sub-menu">--}}
                                        {{--<li class="">--}}
                                            {{--<a href="#" class="js-sub-menu-toggle">--}}
                                                {{--<span class="text">MenuManager Lvl 2</span>--}}
                                                {{--<i class="toggle-icon fa fa-angle-left"></i>--}}
                                            {{--</a>--}}
                                            {{--<ul class="sub-menu">--}}
                                                {{--<li><a href="#">MenuManager Lvl 3</a></li>--}}
                                                {{--<li><a href="#">MenuManager Lvl 3</a></li>--}}
                                                {{--<li><a href="#">MenuManager Lvl 3</a></li>--}}
                                            {{--</ul>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="text">MenuManager Lvl 2</span>--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            </ul>
                        </nav>
                        <!-- /main-nav -->
                        <div class="sidebar-minified js-toggle-minified">
                            <i class="fa fa-angle-left"></i>
                        </div>
                        <!-- sidebar content -->
                        <div class="sidebar-content">
                            {{--<div class="panel panel-default">--}}
                                {{--<div class="panel-heading">--}}
                                    {{--<h5><i class="fa fa-lightbulb-o"></i> Tips</h5>--}}
                                {{--</div>--}}
                                {{--<div class="panel-body">--}}
                                    {{--<p>You can do live search to the widget at search box located at top bar. It's very useful if your dashboard is full of widget.</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<h5 class="label label-default"><i class="fa fa-info-circle"></i> Server Info</h5>--}}
                            {{--<ul class="list-unstyled list-info-sidebar bottom-30px">--}}
                                {{--<li class="data-row">--}}
                                    {{--<div class="data-name">Disk Space Usage</div>--}}
                                    {{--<div class="data-value">--}}
                                        {{--274.43 / 2 GB--}}
                                        {{--<div class="progress progress-xs">--}}
                                            {{--<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%">--}}
                                                {{--<span class="sr-only">10%</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<div class="data-name">Monthly Bandwidth Transfer</div>--}}
                                    {{--<div class="data-value">--}}
                                        {{--230 / 500 GB--}}
                                        {{--<div class="progress progress-xs">--}}
                                            {{--<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%">--}}
                                                {{--<span class="sr-only">46%</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">Database Disk Space</span>--}}
                                    {{--<span class="data-value">219.45 MB</span>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">Operating System</span>--}}
                                    {{--<span class="data-value">Linux</span>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">Apache Version</span>--}}
                                    {{--<span class="data-value">2.4.6</span>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">PHP Version</span>--}}
                                    {{--<span class="data-value">5.3.27</span>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">MySQL Version</span>--}}
                                    {{--<span class="data-value">5.5.34-cll</span>--}}
                                {{--</li>--}}
                                {{--<li class="data-row">--}}
                                    {{--<span class="data-name">Architecture</span>--}}
                                    {{--<span class="data-value">x86_64</span>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        </div>
                        <!-- end sidebar content -->
                    </div>
                    <!-- end left sidebar -->

                    <!-- end top general alert -->
                    <!-- content-wrapper -->
                    <div class="col-md-10 content-wrapper">
                        <div class="row">
                            <div class="col-md-4 ">
                                <ul class="breadcrumb">
                                    <li><i class="fa fa-home"></i><a href="#">Home</a></li>
                                    <li class="active">{{$title }}</li>
                                </ul>
                            </div>
                            <div class="col-md-8 ">
                                <div class="top-content">
                                    <ul class="list-inline mini-stat">


                                        {{--<li>--}}
                                            {{--<h5> <span class="stat-value stat-color-seagreen"><i class="fa fa-plus-circle"></i> 43,748</span></h5>--}}
                                            {{--<span id="mini-bar-chart3" class="mini-bar-chart"></span>--}}
                                        {{--</li>--}}
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
