<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/19/2016
 * Time: 1:39 PM
 */

?>
    <head>
        <title>{!! isset($title) ? $title : "" !!} | Evergreen</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Evergreen - Financial Manager">
        <meta name="author" content="Jason Gallavin">
        <!-- CSS -->
        <link href="{{ asset('theme/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('theme/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('theme/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('theme/css/my-custom-styles.css') }}" rel="stylesheet" type="text/css">

        <!--[if lte IE 9]>
        <link href="{{asset('theme/css/main-ie.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/css/main-ie-part2.css') }}" rel="stylesheet" type="text/css"/>
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
        {!!   \app\libraries\theme\Theme::header() !!}
        {!!  \app\libraries\theme\Theme::header($title) !!}
        @yield('style')
    </head>
