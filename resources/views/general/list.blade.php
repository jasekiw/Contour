<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/11/2016
 * Time: 5:24 PM
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
        <a class="{!! $current === "all" ? "current" : ""  !!}" href="{!! $indexURL !!}">View All</a>
        @foreach($alphabet as $alpha => $link)
            <a class="{!! $current === $alpha ? "current" : ""  !!}" href="{!! $link !!}">{!!$alpha!!}</a>
        @endforeach
    </div>
    <?php
    if(isset($letter))
    {
        $columnManager = new ColumnManager(3,3,2,1, '<div class="row">', '</div>');
        foreach($items as $item)
            $columnManager->add('<div class="list_item"><a href="' . $item->link . '">' . $item->title . '</a></div>');
        echo $columnManager->getHtml();
    }
    else
    {
        $lastLetter = "A";
        $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
        foreach($items as $key => $item)
        {
            if(strtoupper(substr($item->title, 0,1)) != $lastLetter)
            {
                $lastLetter = strtoupper(substr($item->title, 0,1));
                echo $columnManager->getHtml();
                $columnManager = new ColumnManager(3,3,2,1, '<div class="list_item_row row">', '</div>');
            }
            $columnManager->add('<div class="list_item"><a href="' . $item->link . '">' . $item->title . '</a></div>');
        }
        if($columnManager->hasContent())
            echo $columnManager->getHtml();
    }
    ?>
@endsection
