<?php

namespace App\Http\Controllers;

use app\libraries\theme\HeaderTasks;
use app\libraries\theme\Theme;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Controller constructor.
     */
    function __construct()
    {
//        \Session::set('uploads_folder', public_path() . DIRECTORY_SEPARATOR . "uploads");
//        \Session::set('uploads_url',url('uploads'));
//        Theme::construct_theme();
//
////        require_once(base_path() . "/app/libraries/kint/Kint.class.php");
//        //created variables for the theme to use
//        $header_variables = new HeaderTasks();
//        $header_variables->perform();
//
//        require_once(base_path() . "/app/libraries/theme/includes.php");
        // disables query logging. The logging filled up the memory and caused the program to crash.
       // \DB::connection()->disableQueryLog();

//        $GLOBALS['queries'] = array();
//        \Event::listen('illuminate.query', function($query)
//        {
//            array_push($GLOBALS['queries'], $query);
//            //\kint::dump($query);
//        });

    }
}
