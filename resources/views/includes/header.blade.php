<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:39 AM
 */

?>


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

