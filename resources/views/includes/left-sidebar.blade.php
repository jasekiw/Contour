<?php
use app\libraries\contour\Contour;
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/19/2016
 * Time: 1:51 PM
 */

?>

            <div class="col-md-2 left-sidebar">
                <!-- main-nav -->
                <nav class="main-nav">
                    <ul class="main-menu">
                        <li class="active">
                            <a href="#" class="js-sub-menu-toggle">
                                <i class="fa fa-dashboard fa-fw"></i><span class="text">Dashboard</span>
                                {{--<i class="toggle-icon fa fa-angle-down"></i>--}}
                            </a>
                            <ul class="sub-menu open">

                            </ul>
                        </li>
                        <?php
                            $main_menu = Contour::getThemeManager()->getMenuManager()->getAssociatedMenu()
                        ?>
                        @if(isset($main_menu))
                            @foreach($main_menu->getMenuItems() as $menuItem)
                                <li class=""><a href="{!! $menuItem->get_href() !!}"><span class="text">{{$menuItem->getName()}}</span></a></li>
                            @endforeach
                        @endif

                    </ul>
                </nav>
                <!-- /main-nav -->
                <div class="sidebar-minified js-toggle-minified">
                    <i class="fa fa-angle-left"></i>
                </div>
                <!-- sidebar content -->
                <div class="sidebar-content">
                </div>
                <!-- end sidebar content -->
            </div>
