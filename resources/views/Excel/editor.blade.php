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
use app\libraries\theme\userInterface\DataBlockEditor;

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
            <?php $first = true; ?>
            @foreach($tabs as $tab)
                <div class="tab-pane @if($first) active @endif" id="{{$tab->getCodeName()}}">
                    <div class="editor" >
                        <div class="editor__inner_container">
                        @if($tab->excelView->getOrientation() == "column")
                            {!! View::make('partials.excel.sheet', ['sheet' => $tab->excelView]) !!}
                        @else
                            {!! View::make('partials.excel.sheetflipped', ['sheet' => $tab->excelView]) !!}
                        @endif
                        </div>
                    </div>
                </div>
                    <?php $first = false; ?>
            @endforeach
        </div>
    </div>
@endsection
