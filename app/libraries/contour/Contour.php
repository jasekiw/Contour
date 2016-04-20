<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 9:01 PM
 */
namespace app\libraries\contour;


use app\libraries\config\Config;
use app\libraries\contour\routes\RoutesManager;
use app\libraries\theme\Theme;
use app\libraries\theme\ThemeManager;
use app\libraries\user\UserMeta;

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

        self::$theme = new ThemeManager();
        self::$config = new Config();
        self::$userMeta = new UserMeta();
        self::$routesManager = new RoutesManager();
    }

    /**
     * @return ThemeManager
     */
    public static function getThemeManager()
    {
        return self::$theme;
    }

    /**
     * @return Config
     */
    public static function getConfigManager()
    {
        return self::$config;
    }

    /**
     * @return UserMeta
     */
    public static function getUserMetaManager()
    {
        return self::$userMeta;
    }

    /**
     * Gets the Routes Manager
     * @return RoutesManager
     */
    public static function getRoutesManager()
    {
        return self::$routesManager;
    }




}