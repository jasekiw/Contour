<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/2/2015
 * Time: 8:43 AM
 */
use app\libraries\theme\UserInterface\DataBlockEditor;
use app\libraries\theme\data\TableConstructor;

?>

@extends('layouts.default')


@section('content')
<style type="text/css">
    .excel_editor thead td {
        padding: 20px;
    }
    .excel_editor tr {
        border-bottom: 1px solid rgba(0,0,0,0.2);
    }
    .excel_editor td {
        border-right: 1px solid rgba(0,0,0,0.2);
    }
    .excel_editor_container {
        max-width: 100%;
        min-height:550px;
        height:550px;
        overflow: scroll;
    }
    .cell {
        padding: 10px;
        min-width: 200px;
    }
    .column_name {
        display:inline;
    }
    .column_name_container {
        padding: 10px;
        white-space: nowrap;

    }
    .content-wrapper {
        min-height: 100% !important;
    }
    .child_seperator {
        padding-right: 20px;
        text-align: right;
        white-space: nowrap;
        width:60px;

    }
    .back_arrow {
        padding-right: 10px;
    }



</style>

<div class="excel_editor_container">
    <?php
    /**
     * @var \app\libraries\tags\DataTag $sheet
     */
    TableConstructor::printTable($sheet->get_id());
    ?>
</div>


@endsection

@section('scripts')
    <?php
    echo DataBlockEditor::get();
    ?>
    @include('excel.scripts')
@endsection
