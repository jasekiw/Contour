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


    <div class="upper-controls">
        <div class="left">
            @if(isset($backToLinkTitle))
                <a href="{!! $backToLink !!}">Back to {{ $backToLinkTitle }}</a>
            @endif
        </div>
       <div class="right">
           @if(isset($newLink))
            <a href="{!! $newLink !!}"><i class="fa fa-plus"></i> {{$newTitle}}</a>
           @endif
       </div>
    </div>

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
        foreach($items as $item)
            $columnManager->add('<div class="list_item"><a href="' . route("get_facility", array( $item->get_id() ) ) . '">' . $item->get_name() . '</a></div>');
        echo $columnManager->getHtml();
    }
    else
    {

        $lastLetter = "A";
        $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
        foreach($items as $key => $item)
        {
            if(strtoupper(substr($item->get_name(), 0,1)) != $lastLetter)
            {
                $lastLetter = strtoupper(substr($item->get_name(), 0,1));
                echo $columnManager->getHtml();
                $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
            }
            if($item->get_type()->get_id() == Types::get_type_sheet()->get_id())
                $columnManager->add('<div class="list_item"><a href="' . route("sheet_edit", [$item->get_id()] ) . '">' . $item->get_name() . '</a></div>');
            else
                $columnManager->add('<div class="list_item"><a href="' . route("sheetcategories_show", [$item->get_id()] ) . '">' . $item->get_name() . '</a></div>');

        }
        if($columnManager->hasContent())
            echo $columnManager->getHtml();

    }
    ?>

@endsection

@section('scripts')
    {{--@include('facilities.scripts')--}}
@endsection
