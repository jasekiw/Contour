<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/2/2015
 * Time: 11:02 AM
 */

        ?>


@extends('layouts.default')


@section('content')

        <div class="facility_navigation">
                <a class="{{ $current === "all" ? "current" : ""  }}" href="{{ route("facilities") }}">View All</a>
                <?php $alphabet = range('A', 'Z'); ?>
                <?php

                foreach ($alphabet as $alpha) {

                ?>
                <a class="{{ $current === $alpha ? "current" : ""  }}" href="{{route("letter_facilities", array($alpha)) }}">{{$alpha}}</a>
                <?php
                }
                ?>


        </div>


        @if(isset($letter))

                @foreach($reports as $report)

                        <div class="report"><a href="{{route("get_facility", array( $report->get_id() ) ) }}">{{$report->get_name(); }}</a></div>
                @endforeach
        @else
                <?php $lastLetter = "A" ?>
                <div class="report_row">
                        @foreach($reports as $report)

                                <?php
                                if(strtoupper(substr($report->get_name(), 0,1)) != $lastLetter)
                                {
                                        $lastLetter = strtoupper(substr($report->get_name(), 0,1));
                                        echo '</div><div class="report_row">';

                                }

                                ?>
                                <div class="report"><a href="{{route("get_facility", array( $report->get_id() ) ) }}">{{$report->get_name(); }}</a></div>
                        @endforeach
                </div>
        @endif

@endsection

@section('scripts')

@endsection
