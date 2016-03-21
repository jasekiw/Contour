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
use app\libraries\theme\UserInterface\DataBlockEditor;



?>

@extends('layouts.default')


@section('content')

    <style type="text/css">
        .nav-pills>li {
            float: right;
        }
    </style>
<div class="upper-controls">
    <a href="{!! route('sheetcategories_show', [$parent->get_id()]) !!}">Back to {{ $parent->get_name() }}</a>
    <div class="right">
        <a href="{!! route('sheet_delete', [ $tag->get_id() ]) !!}">Delete Sheet</a>
    </div>
</div>


    <div id="sheet_tabbed" class="container">
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
                <?php echo $summaryTable->printTable(false); ?>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <?php
        echo DataBlockEditor::get();
    ?>
    @include('excel.scripts')
@endsection

