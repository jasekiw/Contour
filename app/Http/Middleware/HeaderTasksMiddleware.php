<?php

namespace App\Http\Middleware;

use app\libraries\theme\HeaderTasks;
use app\libraries\theme\Theme;
use Closure;

class HeaderTasksMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->ajax())
        {
            \Session::set('uploads_folder', public_path() . DIRECTORY_SEPARATOR . "uploads");
            \Session::set('uploads_url',url('uploads'));
            Theme::construct_theme();
            require_once(app_path("/libraries/theme/includes.php"));

//        require_once(base_path() . "/app/libraries/kint/Kint.class.php");
            //created variables for the theme to use
            $header_variables = new HeaderTasks();
            $header_variables->perform();
        }



        return $next($request);
    }
}
