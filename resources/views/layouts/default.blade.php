<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/1/2015
 * Time: 8:50 AM
 */
 ?>

@include("includes.header")

@if(isset($message))
    <p class="message" style="color:green;">{{$message}}</p>
@endif

    @yield('content')
<div class="console">

</div>
            <!-- /main-content -->
@include("includes.footer")
