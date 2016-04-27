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
/**
 * @var MenuItem[] $menuItems
 */



?>


@extends('layouts.default')


@section('content')


    <div class="upper-controls">
        <div class="right">
            <a href="{!! route('menu.index') !!}">Cancel</a>
        </div>
    </div>
    <div class="menuEditor" menu="{!! $menu->get_id() !!}">

        {!! Form::open(['method' => 'PUT', 'url' => route('menu.update', $menu->get_id() ), 'class' => 'resource_form'] )!!}
            <div class="inside">
                <div class="row headerRow">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('Title') !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('Link') !!}
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="links">
                    <?php
                    foreach($menuItems as $menuItem)
                    {
                        ?>
                        <div class="row menuLink" order="<?php echo $menuItem->get_sort_number() ?>">
                            <div class="col-md-2 grabber">
                                <i class="fa fa-bars "></i>
                            </div>
                            <div class="col-md-4">
                                {!! Form::input('text', 'name', $menuItem->getName()) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::input('text', 'href',$menuItem->get_href()) !!}
                            </div>
                            <div class="col-md-2 delete">
                                <i class="fa fa-trash fa-3"></i>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row newRow">
                    <div class="col-md-2 grabber">

                    </div>
                    <div class="col-md-8 new">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="col-md-2 delete">

                    </div>
                </div>
            </div>
            <div class="row">
                {!! Form::submit('Save') !!}
            </div>
        {!! Form::close() !!}
    </div>


@endsection

@section('scripts')
  <script type="text/javascript">
//      var editor = new MenuEditor($(".menuEditor"));
  </script>
@endsection