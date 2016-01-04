<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 10:28 AM
 */
?>
@include("includes.install.header")
    @if( session('message'))
        <div class="alert alert-danger">
            <strong>{{ session('message') }}</strong>
        </div>
    @endif
    @yield('content')
        <!-- /main-content -->
@include("includes.install.footer")