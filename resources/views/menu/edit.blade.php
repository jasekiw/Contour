<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/3/2015
 * Time: 2:38 PM
 */
use app\libraries\theme\menu\item\MenuItem;
/**
 * @var MenuItem $menuItem
 */
/**
 * @var Illuminate\Routing\Route $route
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
    {!!
    Form::open( ["url" => route("create_menu_item"), 'method' => "POST" ])!!}
    {!!Form::hidden('menu', $menu->get_id())!!}
    {!!Form::label('Name')!!}
    {!!Form::input('text', 'name')!!}
    {!!Form::label('Link')!!}
    {!!Form::input('text', 'link')!!}
    {!!Form::label('Icon')!!}
    {!!Form::input('text', 'icon')!!}
    {!!Form::submit("Create")!!}
    {!!Form::close()!!}


    <table class="menuItemEditor">
        <thead>
            <th>Name</th>
            <th>Url / route</th>
            <th>Icon</th>
        </thead>
        <tbody>



        <?php

        foreach($menuItems as $menuItem)
        {


        ?>
            <tr>
                <td><a href="{!! route("menu_item_edit", array($menuItem->get_id())) !!}">{!! $menuItem->getName()!!} </a></td>
                <td>{!!$menuItem->get_href()!!}</td>
                <td>{!!$menuItem->get_icon()!!}</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
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

