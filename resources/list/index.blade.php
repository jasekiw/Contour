<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/9/2015
 * Time: 4:05 PM
 */

/**
 * This view requires
 * $backToLinkTitle or
 */

/**
 * @var DataTag $facility
 * @var DataTag[] $items
 */
use \app\libraries\tags\DataTag;
use app\libraries\theme\data\ColumnManager;
use app\libraries\types\Types;

?>

@extends('layouts.default')


@section('content')

    @if(isset($backToLinkTitle))
        <a href="{!! $backToLink !!}">Back to {{ $backToLinkTitle }}</a>
    @endif

    <div class="list_navigation">
        <a class="{!! $current === "all" ? "current" : ""  !!}" href="{!! isset($parent) ?  route("sheetcategories_show", [$parent->get_id()]) : route('sheetcategories_index') !!}">View All</a>
        <?php $alphabet = range('A', 'Z'); ?>
        @foreach($alphabet as $alpha)
            <a class="{!! $current === $alpha ? "current" : ""  !!}" href="{!! isset($parent) ?  route("sheetcategories_show_letter", [$parent->get_id(), $alpha]) : route('sheetcategories_index_letter', [$alpha]) !!}">{!!$alpha!!}</a>
        @endforeach
    </div>

    <?php
    if(isset($letter))
    {
        $columnManager = new ColumnManager(3,3,2,1, '<div class="row">', '</div>');
        foreach($items as $facility)
        {

            $columnManager->add('<div class="list_item"><a href="' . route("get_facility", array( $facility->get_id() ) ) . '">' . $facility->get_name() . '</a></div>');
        }
        echo $columnManager->getHtml();
    }
    else
    {

        $lastLetter = "A";
        $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
        foreach($items as $key => $facility)
        {
            if(strtoupper(substr($facility->get_name(), 0,1)) != $lastLetter)
            {
                $lastLetter = strtoupper(substr($facility->get_name(), 0,1));
                echo $columnManager->getHtml();
                $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
            }
            if($facility->get_type()->get_id() == Types::get_type_sheet()->get_id())
            {
                $columnManager->add('<div class="list_item"><a href="' . route("sheet_edit", array( $facility->get_id() ) ) . '">' . $facility->get_name() . '</a></div>');
            }
            else
            {
                $columnManager->add('<div class="list_item"><a href="' . route("sheetcategories_show", array( $facility->get_id() ) ) . '">' . $facility->get_name() . '</a></div>');
            }

        }
        if($columnManager->hasContent())
            echo $columnManager->getHtml();

    }
    ?>

@endsection

@section('scripts')
    @include('facilities.scripts')
@endsection
