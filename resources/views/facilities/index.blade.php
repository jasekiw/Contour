<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/9/2015
 * Time: 4:05 PM
 */
/**
 * @var DataTag $facility
 * @var DataTag[] $facilities
 */
use \app\libraries\tags\DataTag;

?>

@extends('layouts.default')


@section('content')

    <div class="facility_navigation">
        <a class="{!! $current === "all" ? "current" : ""  !!}" href="{!!   route("facilities") !!}">View All</a>
        <?php
        $alphabet = range('A', 'Z');
        foreach ($alphabet as $alpha)
        {
            ?>
             <a class="{!! $current === $alpha ? "current" : ""  !!}" href="{!! route("letter_facilities", array($alpha)) !!}">{!!$alpha!!}</a>
            <?php
        }
        ?>
    </div>

    <?php
    if(isset($letter))
    {
        $columnManager = new \app\libraries\theme\data\ColumnManager(3,3,2,1, '<div class="row">', '</div>');
        foreach($facilities as $facility)
        {

           $columnManager->add('<div class="facility"><a href="' . route("get_facility", array( $facility->get_id() ) ) . '">' . $facility->get_name() . '</a></div>');
        }
        echo $columnManager->getHtml();
    }
    else
    {

        $lastLetter = "A";
        $columnManager = new \app\libraries\theme\data\ColumnManager(3,3,2,1, '<div class="facility_row row">', '</div>');
        foreach($facilities as $key => $facility)
        {

            if(strtoupper(substr($facility->get_name(), 0,1)) != $lastLetter)
            {
                $lastLetter = strtoupper(substr($facility->get_name(), 0,1));
                echo $columnManager->getHtml();
                $columnManager = new \app\libraries\theme\data\ColumnManager(3,3,2,1, '<div class="facility_row row">', '</div>');
            }
            $columnManager->add('<div class="facility"><a href="' . route("get_facility", array( $facility->get_id() ) ) . '">' . $facility->get_name() . '</a></div>');


        }

    }
    ?>




@endsection

@section('scripts')
    @include('facilities.scripts')
@endsection
