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

    }
    protected function render($response)
    {
        if(is_string($response))
            return $response;
        if(!method_exists($response, 'render'))
            return $response;
        $actionName = $this->getRouter()->getCurrentRoute()->getActionName();
        $response->responseAction = str_replace("\\", " ",
            str_replace("App\\Http\\Controllers\\", "",$actionName)
        );

        if(!isset($response->title) && str_contains($actionName, "@"))
        {
            $controllerPos = strrpos($actionName, "Controller");
            $atLocation = strrpos($actionName, "@");
            if($controllerPos !== false && $atLocation !== false)
            {
                $lastSlash = strrpos($actionName, "\\") + 1;
                if($lastSlash !== false)
                $response->title = ucwords(substr($actionName, $lastSlash, $controllerPos - $lastSlash)) . " - " . ucwords(substr($actionName, $atLocation + 1));
            }
        }


        return $response;
    }
}
