<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/2/2015
 * Time: 10:52 AM
 */
/**
 * @var DataTag[] $columns
 * @var TableBuilder $summaryTable
 * @var TableBuilder $revenueTable
 * @var TableBuilder $expensesTable
 */
use \app\libraries\tags\DataTag;
use app\libraries\theme\data\TableBuilder;



?>

@extends('layouts.default')


@section('content')

    <style type="text/css">
        .nav-pills>li {
            float: right;
        }
    </style>



    <div id="facilityTabbed" class="container">
        <ul  class="nav nav-pills">
            @foreach($compositTags as $compositTag)
                <li>
                    <a href="#{{$compositTag->get_name()}}" data-toggle="tab">{{$compositTag->get_name()}}</a>
                </li>
            @endforeach

            <li class="active">
                <a href="#summary" data-toggle="tab">Summary</a>
            </li>
        </ul>

        <div class="tab-content clearfix">
            @foreach($compositTables as $compositTable)
                <div class="tab-pane" id="{{$compositTable->getName()}}">
                    {!! $compositTable->printTable() !!}
                </div>
            @endforeach
            <div class="tab-pane active" id="summary">
                <?php echo $summaryTable->printTable(); ?>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('facilities.scripts')
@endsection
