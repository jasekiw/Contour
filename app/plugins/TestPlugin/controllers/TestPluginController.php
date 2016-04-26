<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/7/2016
 * Time: 11:56 PM
 */

namespace app\plugins\TestPlugin\controllers;


use App\Http\Controllers\Controller;

class TestPluginController extends Controller
{

    public function index()
    {
        $view =  view()->file('../views/index.php');

        $view->title = "hello";

        return 'hello';

    }
}