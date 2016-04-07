<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/7/2016
 * Time: 1:50 AM
 */
/**
 * @var \app\libraries\excel\ExcelView $laborTemplate
 */
$tab =  $laborTemplate->getNavTitles()[0];
?>

@extends('layouts.default')


@section('content')

<div class="newfacility">
    <div class="step1">
        <h1 class="centered">Facility Name</h1>
        <input class="form-control input-lg" />
        <h1 class="centered">Add Employees</h1>
        <div class="labor_adding">

            {!! View::make('partials.excel.sheet', ['sheet' => $tab->excelView->summarySheet]) !!}
            {!! View::make('partials.excel.table', ['table' => $tab->excelView->summaryTable]) !!}
            {!! View::make('partials.excel.sheet', ['sheet' => $tab->excelView->summaryHybrid]) !!}
            {!! View::make('partials.excel.properties', ['propertiesView' => $tab->excelView->propertysView]) !!}

        </div>
        <button class="btn btn-default floatright" onclick="javascript:main.newFacilityPage.toProperties()">next</button>
    </div>
    <div class="step2">
        <h1 class="centered" >Add Properties</h1>
        <button class="btn btn-default floatleft" onclick="javascript:main.newFacilityPage.backToStart()">back</button>
        <button class="btn btn-default floatright" onclick="javascript:main.newFacilityPage.finish()">finish</button>
    </div>

</div>



@endsection
@section('scripts')

    @include('sheets.create_facility_scripts')
@endsection