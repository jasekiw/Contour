<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/22/2015
 * Time: 3:35 PM
 */
use app\libraries\theme\userInterface\DataBlockEditor;
use app\libraries\theme\data\TableConstructor;

?>

@extends('layouts.default')


@section('content')
    <style type="text/css">
        .excel_viewer thead th {
            padding: 20px;
        }
        .excel_viewer tr {
            border-bottom: 1px solid rgba(0,0,0,0.2);
        }
        .excel_viewer td {
            border-right: 1px solid rgba(0,0,0,0.2);
        }
        .excel_viewer_container {
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

    <div class="excel_viewer_container" sheet="<?php echo $sheetId ?>">
        <?php
        /**
         * @var \app\libraries\tags\DataTag $sheet
         */
        set_time_limit(3600);
        //TableConstructor::printTable($sheet->get_id(), true);
        ?>
    </div>


@endsection

@section('scripts')
    @include('excel.scripts')
@endsection