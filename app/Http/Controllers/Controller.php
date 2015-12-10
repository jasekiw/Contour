<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function __construct()
    {
        \Contour::construct();
        \Theme::construct_theme();


        require_once(base_path() . "/app/libraries/kint/Kint.class.php");
        //created variables for the theme to use
        $header_variables = new \app\libraries\theme\HeaderTasks();
        $header_variables->perform();

        // disables query logging. The logging filled up the memory and caused the program to crash.
        \DB::connection()->disableQueryLog();

        //$GLOBALS['queries'] = array();
        //Event::listen('illuminate.query', function($query)
        //{
        //    array_push($GLOBALS['queries'], $query);
        //    //\kint::dump($query);
        //});

    }
}
