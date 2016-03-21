<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/12/2015
 * Time: 10:28 AM
 */
        ?>

        <!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>404 Page Not Found | {!! $company_name  !!} - {!! $website_name  !!}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="{!! $website_description  !!}">
    <meta name="author" content="{!!$developer !!}">

    <!-- CSS -->
    <link href="{!! asset('assets/css/bootstrap.min.css')  !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/font-awesome.min.css')  !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/main.css')  !!}" rel="stylesheet" type="text/css">

    <!--[if lte IE 9]>
    <link href="{!! asset('assets/css/main-ie.css')  !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('assets/css/main-ie-part2.css')  !!}" rel="stylesheet" type="text/css" />
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{!! $favicon_url !!}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{!! $favicon_url !!}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{!!$favicon_url  !!}">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{!! $favicon_url !!}">
    <link rel="shortcut icon" href="{!! $favicon_url  !!}">

</head>

<body>
<div class="wrapper full-page-wrapper page-error text-center">
    <div class="inner-page">
        <h1>
				<span class="clearfix title">
					<span class="number">404</span> <span class="text">Oops! <br/>Page Not Found</span>
				</span>
        </h1>

        <p>The page you were looking for could not be found, please <a href="TODO:addcontactemail">contact us</a> to report this issue.</p>
        <p>You can also use search form below to find the page you are looking for.</p>
        {{--<form class="searchbox center-block">--}}
            {{--<div class="input-group input-group-lg">--}}
                {{--<input type="search" placeholder="type keyword here" class="form-control">--}}
					{{--<span class="input-group-btn">--}}
						{{--<button class="btn btn-primary" type="button"><i class="fa fa-search"></i> Search</button>--}}
					{{--</span>--}}
            {{--</div>--}}
        {{--</form>--}}
        <div>
            <a href="javascript:history.go(-1)" class="btn btn-custom-primary"><i class="fa fa-arrow-left"></i> Go Back</a>
            <a href="{!! route('home') !!}" class="btn btn-primary"><i class="fa fa-home"></i> Home</a>
        </div>
    </div>
</div>

{!! $copyright_html !!}

<!-- Javascript -->
<script src="{!! asset('assets/js/jquery/jquery-2.1.0.min.js')  !!}"></script>
<script src="{!! asset('assets/js/bootstrap/bootstrap.js')  !!}"></script>
<script src="{!! asset('assets/js/plugins/modernizr/modernizr.js')  !!}"></script>

</body>

</html>


