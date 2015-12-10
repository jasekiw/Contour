<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/3/2015
 * Time: 11:32 AM
 */

use app\libraries\theme\menu\item\MenuItem;
/**
 * @var MenuItem[] $menuItems
 */
?>


@extends('layouts.default')


@section('content')

    {{
    Form::open(
    array(
    "action" => "create_menu",
    'method' => "POST"
    ));
    }}

    {{Form::input('text', 'name')}}
    {{Form::submit("Create")}}
    {{Form::close()}}

    <?php
    $menus =  Contour::getThemeManager()->getMenuManager()->getMenus();
    foreach($menus as $menu)
    {
    ?>
    <a href="{{ route("get_menu", array($menu->get_id())) }}">{{ $menu->getName()}} </a>
    <?php
    }
    ?>


@endsection

