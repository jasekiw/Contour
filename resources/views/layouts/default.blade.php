<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/1/2015
 * Time: 8:50 AM
 */
 ?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
    @include("includes.head")

    <body class="dashboard @if(isset($class)) {!! $class !!} @endif">
        <!-- WRAPPER -->
        <div class="wrapper">
            @include("includes.topbar")
            <!-- BOTTOM: LEFT NAV AND RIGHT MAIN CONTENT -->
            <div class="bottom">
                <div class="container">

                    <div class="row">
                        <!-- left sidebar -->
                        @include('includes.left-sidebar')
                        <!-- end left sidebar -->

                        <!-- end top general alert -->
                        <!-- content-wrapper -->
                        <div class="col-md-10 content-wrapper">
                            @include("includes.header")

                            <!-- main -->
                            <div class="content">
                                <div class="main-header @if(isset($subtitle)) hasSubtitle @endif">
                                    <h2>{{$title }}</h2>
                                    @if(isset($subtitle))
                                        <em>{{$subtitle}}</em>
                                    @endif
                                </div>

                                <div class="main-content">

                                    @if(isset($message))
                                        <p class="message" style="color:green;">{{$message}}</p>
                                    @endif

                                        @yield('content')
                                    <div class="console">
                                    </div>

                                </div><!-- main-content-->
                            </div>
                            <!-- /main -->
                        </div>
                        <!-- /content-wrapper -->
                    </div>
                    <!-- /row -->
                </div>
                <!-- /container -->
            </div>
            <!-- END BOTTOM: LEFT NAV AND RIGHT MAIN CONTENT -->
            <!-- FOOTER -->
        </div>
        <!-- /wrapper -->
    @include("includes.footer")
    </body>
</html>
