<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 10:28 AM
 */
?>
@include("includes.login.header")
@if(Session::has('message'))
    <p style="color:green;">{{Session::get('message')}}</p>
@endif

@yield('content')

<!-- /main-content -->
@include("includes.login.footer")