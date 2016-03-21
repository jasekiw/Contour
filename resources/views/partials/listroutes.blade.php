<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/21/2016
 * Time: 3:49 PM
 */
?>
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