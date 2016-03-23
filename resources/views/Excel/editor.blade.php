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

/**
 * @var \app\libraries\excel\ExcelView $sheet
 */

$tabs =  $sheet->getNavTitles();

?>

@extends('layouts.default')


@section('content')

    <style type="text/css">
        .nav-container {
            width:100%;
            display:inline-block;
        }
        .nav.nav-pills {
            display: inline-block;
            float: right;
        }

        .nav-pills>li {
            float: left;
        }
    </style>
<div class="upper-controls">
    <a href="{!! route('sheetcategories_show', [$parent->get_id()]) !!}">Back to {{ $parent->get_name() }}</a>
    <div class="right">
        <a href="{!! route('sheet_delete', [ $tag->get_id() ]) !!}">Delete Sheet</a>
    </div>
</div>


    <div id="sheet_tabbed" class="container">
        <div class="nav-container">
            <ul  class="nav nav-pills">

                @foreach($tabs as $key => $tab)
                    <li @if($key == 0) class="active" @endif>
                        <a href="#{{$tab->getCodeName()}}" data-toggle="tab" >{{$tab->name}}</a>
                    </li>
                @endforeach

            </ul>
        </div>
        <div class="tab-content clearfix">
            @foreach($tabs as $tab)
                <div class="tab-pane" id="{{$tab->getCodeName()}}">
                    <?php $summarySheet = $tab->excelView->summarySheet; ?>
                    {!! View::make('partials.excel.sheet', ['sheet' => $summarySheet]) !!}

                </div>
            @endforeach

        </div>

    </div>

@endsection

@section('scripts')
    <?php
        echo DataBlockEditor::get();
    ?>
    @include('excel.scripts')
@endsection

