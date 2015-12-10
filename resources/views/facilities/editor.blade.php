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
            <li>
                <a href="#revenue" data-toggle="tab">Revenue</a>
            </li>
            <li>
                <a href="#expenses" data-toggle="tab">Expenses</a>
            </li>
            <li class="active">
                <a href="#summary" data-toggle="tab">Summary</a>
            </li>
        </ul>

        <div class="tab-content clearfix">
            <div class="tab-pane" id="revenue">

                <?php echo $revenueTable->printTable(); ?>
            </div>
            <div class="tab-pane" id="expenses">
                <?php echo $expensesTable->printTable(); ?>
            </div>
            <div class="tab-pane active" id="summary">
                <?php echo $summaryTable->printTable(); ?>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('facilities.scripts')
@endsection
