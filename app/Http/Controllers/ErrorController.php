<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class ErrorController
 * @package App\Http\Controllers
 */
class ErrorController extends Controller
{

    public function show()
    {
        $view = \View::make('errors.404');
        $view->title = "404";
        return $view->render();
    }

}
