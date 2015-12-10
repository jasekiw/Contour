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

    /**
     * Instantiates the class
     */
    function __construct()
    {
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

}