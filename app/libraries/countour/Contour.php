<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 9:01 PM
 */


/**
 * Class System
 */
class Contour
{
    /**
     * @var Theme
     */
    private static $theme = null;
    private static $config = null;
    private static $userMeta = null;
    private static $routesManager = null;


    public static function construct()
    {

        self::$theme = new \app\libraries\theme\ThemeManager();
        self::$config = new \app\libraries\config\Config();
        self::$userMeta = new \app\libraries\user\UserMeta();
        self::$routesManager = new \app\libraries\contour\routes\RoutesManager();
    }

    /**
     * @return \app\libraries\theme\ThemeManager
     */
    public static function getThemeManager()
    {
        return self::$theme;
    }

    /**
     * @return \app\libraries\config\Config
     */
    public static function getConfigManager()
    {
        return self::$config;
    }

    /**
     * @return \app\libraries\user\UserMeta
     */
    public static function getUserMetaManager()
    {
        return self::$userMeta;
    }

    /**
     * Gets the Routes Manager
     * @return \app\libraries\contour\routes\RoutesManager
     */
    public static function getRoutesManager()
    {
        return self::$routesManager;
    }




}