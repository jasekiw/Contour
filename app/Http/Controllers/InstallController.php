<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InstallController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = \View::make('install.index');
        $view->title = "Install";
        $installedAlready = \Schema::hasTable('config');
        $view->installedAlready = $installedAlready;
        return $this->render($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Schema::hasTable('config'))
            return;
        \Artisan::call('migrate');
        $output = "";
        $output .= \Artisan::output();
        \Artisan::call('db:seed');
        $output .= \Artisan::output();
        $view = \View::make('install.index');
        $view->finished = true;
        $view->output = $output;
        $view->title = "Finished Installing";
        return $this->render($view);
    }

}
