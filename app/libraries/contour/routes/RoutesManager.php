<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 10:19 PM
 */

namespace app\libraries\contour\routes;

/**
 * Class RoutesManager
 * @package app\libraries\contour\routes
 */
class RoutesManager
{
    private $routesCollection = null;
    private $routes;

    /**
     * Instantiates the class
     */
    function __construct()
    {
        $this->routes = [];
        $this->routesCollection = new RoutesCollection();
    }

    /**
     * Routes a url GET to a class file and function
     * @param $url
     * @param $name
     * @param $object
     * @param $function
     * @param $accessGroupID
     */
    public function routeGet($url, $name, $object, $function, $accessGroupID)
    {
        //TODO: add route logic
    }

    /**
     * Routes a url POST to a class file and function
     * @param $url
     * @param $name
     * @param $object
     * @param $function
     * @param $accessGroupID
     */
    public function routePost($url, $name, $object, $function, $accessGroupID)
    {
        //TODO: add route logic
    }

    public function hasPostUrl($url)
    {

    }
    public function hasGetUrl($url)
    {

    }

    public function callRoutePost($id)
    {

    }

    public function callRouteGet($id)
    {

    }
    public function printRoutes()
    {
        $pluginfiles = scandir (app_path('plugins'));
        if(isset($pluginfiles[0]) && $pluginfiles[0] = '.')
            unset($pluginfiles[0]);
        if(isset($pluginfiles[1]) && $pluginfiles[1] = '...')
            unset($pluginfiles[1]);
        $pluginsDirectories = [];
        foreach($pluginfiles as $pluginFile)
        {
            if(is_dir(app_path('plugins') . DIRECTORY_SEPARATOR . $pluginFile))
                array_push($pluginsDirectories, app_path('plugins') . DIRECTORY_SEPARATOR . $pluginFile);
        }
        foreach($pluginsDirectories as $pluginsDirectory)
        {
            if(file_exists($pluginsDirectory . DIRECTORY_SEPARATOR . 'routes.php'))
                require $pluginsDirectory . DIRECTORY_SEPARATOR . 'routes.php';
        }


    }

    /**
     * This is a helper method to generate a resource route names correctly
     *
     * USAGE:
     *
     * Route::resource('testgroups','UserAccessGroupsController',  ['middleware' => 'auth',
     *
     *      'names' => Contour::getRoutesManager()->getResourceRoutesForNameHelper('usergroups')
     *
    *   ] );
     * @param $name
     * @return array
     */
    public function getResourceRoutesForNameHelper($name)
    {
        return [
            'index' => $name . ".index",
            'create' => $name . ".create",
            'store' => $name . ".store",
            'show' => $name . ".show",
            'edit' => $name . ".edit",
            'update' => $name . ".update",
            'destroy' => $name . ".destroy",
        ];
    }

}