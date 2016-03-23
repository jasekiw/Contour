<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 11:50 AM
 */

?>
@extends('layouts.default')


@section('content')
<style type="text/css">
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    .spaced {
        margin: 10px 0;
    }
</style>
    <div class="row">
        <div class="col-xs-12">
             <span class="btn btn-default btn-file spaced">
                Select Excel File <input type="file">
            </span>
            <div class="form-group spaced">
                <label for="sel1">Select Import Template</label>
                <select class="form-control" id="sel1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
            </div>
            <div class="input-group spaced">
                <span class="input-group-addon" id="basic-addon1">location to import</span>
                <input type="text" class="form-control" placeholder="/templates" aria-describedby="basic-addon1">
            </div>
            <button type="button" class="btn btn-default spaced">Import</button>
            <hr />
        </div>

    </div>



    @endsection
