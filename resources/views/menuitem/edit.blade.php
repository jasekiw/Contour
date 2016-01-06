<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/6/2016
 * Time: 9:07 AM
 */
?>
@extends('layouts.default')


@section('content')

<style type="text/css">
    .menuItemEditor
    {
        border:1px solid black;
        margin-top: 100px;
    }
    .menuItemEditor td,  .menuItemEditor th{
        padding: 10px 40px;
        border-right:1px solid black;
    }
    .menuItemEditor thead {
        border-bottom:1px solid black;
    }
</style>
{!! Form::open( ["url" => route("menu_item_update", ['id' => $menuItem->get_id()]), 'method' => "PUT" ])!!}
{!!Form::label('Name')!!}
{!!Form::input('text', 'name', $menuItem->getName())!!}
{!!Form::label('Link')!!}
{!!Form::input('text', 'link', $menuItem->get_href())!!}
{!!Form::label('Icon')!!}
{!!Form::input('text', 'icon', $menuItem->get_icon())!!}
{!!Form::submit("Save")!!}
{!!Form::close()!!}


<table class="menuItemEditor">
    <thead>
    <th>Routes Available</th>
    </thead>
    @foreach(Route::getRoutes()->getRoutes() as $route)
        @if(strlen($route->getName()) > 0)
            <tr>
                <td>
                    {!!$route->getName()!!}
                </td>
            </tr>
        @endif

    @endforeach
</table>

@endsection