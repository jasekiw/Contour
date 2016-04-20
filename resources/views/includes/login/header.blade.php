<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:39 AM
 */
?>

        <!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>{!!isset($title) ? $title : ""!!} | Evergreen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Evergreen - Financial Manager">
    <meta name="author" content="Jason Gallavin">

    <!-- CSS -->
    <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/my-custom-styles.css') !!}" rel="stylesheet" type="text/css">

    <!--[if lte IE 9]>
    <link href="{!! asset('assets/css/main-ie.css') !!}" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('assets/css/main-ie-part2.css') !!}" rel="stylesheet" type="text/css"/>
    <![endif]-->

    <!-- CSS for demo style switcher. you can remove this -->
    <!--<link href="demo-style-switcher/assets/css/style-switcher.css" rel="stylesheet" type="text/css">-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{!! asset('assets/ico/favicon.ico') !!}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{!! asset('assets/ico/favicon.ico') !!}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{!! asset('assets/ico/favicon.ico') !!}">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{!! asset('assets/ico/favicon.ico') !!}">
    <link rel="shortcut icon" href="{!! asset('assets/ico/favicon.ico') !!}">


    {!! app\libraries\theme\Theme::header() !!}
    {!! app\libraries\contour\Contour::getThemeManager()->head() !!}
</head>

<body>
    <div class="wrapper full-page-wrapper page-auth page-login text-center">
        <div class="inner-page">
            <div class="logo">
                <a href="{!! route('home') !!}"><img src="@if(isset($logo_url)) {!! $logo_url !!} @endif" alt="" /></a>
            </div>


            <div class="login-box center-block">