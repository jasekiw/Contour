<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/9/2015
 * Time: 4:13 PM
 */
use app\libraries\tags\DataTag;

/* @var DataTag $currently_viewing */
?>



@extends('layouts.default')


@section('content')
<style type="text/css">
    .content .tags {
        list-style: none;

    }
    .content .tags li .tag {
        min-height: 100px;
        padding: 20px;
        display: inline-block;


        border: 1px solid rgba(0,0,0,0.3);
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.4);



        margin:10px;
    }



    .content .tags li{
        min-height: 100px;
        display: inline-block;

    }
    .hasChildren {
        color:red;
    }
    .controls {
        list-style: none;
    }
    .hover {
        /*border-left: 2px solid rgba(255,255,0, 0.5);*/
    }
    .dropRight {
        border-right: 2px solid rgba(255,255,0, 0.5);
    }
    .dropLeft {
        border-left: 2px solid rgba(255,255,0, 0.5);
    }
    .wrapper {
        overflow: hidden;
    }
    .dropIn {
        background-color: rgba(255,255,0, 0.1);
    }

    .excluded {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

</style>


        <ul class="controls">
            <li class="create_tag"><a href="javascript: editor.create()">Create Tag</a></li>
        </ul>


    <ul class="tags tag_{!! isset($currently_viewing) ? $currently_viewing->get_id() : -1 !!}">

        <?php
             /**
            * @var app\libraries\tags\collection\TagCollection $tags
            */

        $parent = null;
        if (isset($currently_viewing)) {
            $parent = $currently_viewing->get_parent();

            if (isset($parent)) {
                echo '<li><a class="tag excluded tag_' . $parent->get_id() . '" href="' . URL::action('TagController@show', [$parent->get_id()]) . '">';
                echo '...';
                echo '</a></li>';
            } else {
                echo '<li><a class="tag excluded tag_-1" href="' . route('tag_index') . '">';
                echo '...';
                echo '</a></li>';
            }
        }

        foreach ($tags->getAsArray(\app\libraries\tags\collection\TagCollection::SORT_TYPE_ALPHABETICAL) as $tag) {
            /**
             * @var app\libraries\tags\DataTag $tag
             */
            if ($tag->has_children()) {
                $contextmenue = UserInterface::makeContextMenu(array(
                        array('title' => 'open as Excel', 'url' => URL::action('ExcelController@edit', [$tag->get_id()])),
                        array('title' => 'rename', 'url' => 'javascript: editor.rename(' . $tag->get_id() . ')'),
                        array('title' => 'delete', 'url' => 'javascript: editor.delete(' . $tag->get_id() . ')')

                ));
                echo '<li><a class="tag has_menu tag_' . $tag->get_id() . ' hasChildren context type_' . $tag->get_type()->getName() . '" data-toggle="context" data-target="#' . $contextmenue . '" href="' . URL::action('TagController@show', [$tag->get_id()]) . '">';
                echo $tag->get_name();
                echo '</a></li>';
            } else {
                $contextmenue = UserInterface::makeContextMenu(array(
                        array('title' => 'rename', 'url' => 'javascript: editor.rename(' . $tag->get_id() . ')'),
                        array('title' => 'delete', 'url' => 'javascript: editor.delete(' . $tag->get_id() . ')')

                ));

                echo '<li><a class="tag has_menu tag_' . $tag->get_id() . ' context type_' . $tag->get_type()->getName() . '" data-toggle="context" data-target="#' . $contextmenue . '" href="#" >';
                echo $tag->get_name();
                echo '</a></li>';
            }

        }
        ?>


    </ul>
@endsection


@section('scripts')
    @include('tags.scripts')
@endsection


