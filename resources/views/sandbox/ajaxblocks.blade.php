<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/4/2015
 * Time: 4:00 PM
 */
?>


@extends('layouts.default')


@section('content')
{{Form::open(array("method" => "POST", "action" => "multiple_ajax_datablock"))}}
    {{Form::hidden('tags[]', "1")}}
{{Form::hidden('tags[]', "2")}}
{{Form::hidden('tags[]', "3")}}
{{Form::submit()}}

{{form::close()}}

@endsection

@section('scripts')
    @include('sandbox.scripts')
@endsection

<style type="text/css">
    #DatablockEditor {
        border: 1px solid black;
        padding:20px;
        margin:40px;
        display:none;
    }
    #DatablockEditor .top_section {
        padding: 20px;
    }
    #DatablockEditor .top_section [name=datablock_value]
    {
        width:100%;
        height:50px;
    }
    #DatablockEditor .bottom_section {
        padding: 20px;
    }
    #DatablockEditor .datablock_view {
        border: 1px solid black;
        min-height:100px;
        margin-top:40px;
    }
</style>
<div id="DatablockEditor">
    <div class="top_section">
        <input type="text" name="datablock_value" />
    </div>
    <div class="bottom_section">
        <div class="datablock_controls">
            <a class="control" href="javascript:datablockeditor.up">Up</a>
            <input type="text" name="search" />
        </div>
        <div class="datablock_view">

            <table class="datablocks">
                <thead class="header_container">

                </thead>
                <tbody class="row_and_datablock_container">

                </tbody>
            </table>
        </div>
    </div>
</div>
