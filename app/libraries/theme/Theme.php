<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/12/2015
 * Time: 4:30 PM
 */




class Theme
{

    private static $enqueue_scripts = array();
    private static $enqueue_styles = array();
    private static $specific_enqueue_scripts = array();
    private static $specific_enqueue_styles = array();
    private static $footertasks = array();
    private static $menu = null;
    private static $ajax_manager = null;
    private static $theme = null;

    /**
     * @requires $name is not null. $src is not null
     * @param string $name The name of the script
     * @param string $src the location of the script to load relative to the public foler. do not include public. ex 'assets/js'
     * @param string $group The title of the page to enqueue this script to. the title tag is represented by $view->title
     */
    public static function enqueue_script( $name,  $src, $group = null  )
    {
        $src = asset($src);
        $script = '<script type="text/javascript" class="' . $name . '_script" src="' . $src . "\" ></script>";

        if(isset($group))
        {
            $group = strtoupper($group);
            if(!isset(self::$specific_enqueue_scripts[$group]))
            {
                self::$specific_enqueue_scripts[$group] = array();
            }
            self::$specific_enqueue_scripts[$group][$name] = $script;
        }
        else{

            self::$enqueue_scripts[$name] = $script;
        }

    }

    /**
     * Returns the theme's menu object
     */
    public static function getMenue()
    {
        if(!isset(self::$menu))
        {
            self::$menu = new \app\libraries\theme\menu\MenuManager();
        }

        return self::$menu;
    }



    /**
     *
     * @param string $name
     * @param string $value
     * @param null $group
     */
    public static function enqueue_inline_script( $name,  $value, $group = null )
    {
        $script = '<script type="text/javascript" class="' . $name . '_script">' . $value . "</script>";
        if(isset($group))
        {
            $group = strtoupper($group);
            if(!isset(self::$specific_enqueue_scripts[$group] ))
            {
                self::$specific_enqueue_scripts[$group] = array();
            }
            self::$specific_enqueue_scripts[$group][$name] = $script;
        }
        else{

            self::$enqueue_scripts[$name] = $script;
        }
    }

    /**
     * @param string $name
     * @param string $src
     * @param null $group
     */
    public static function enqueue_style( $name,  $src, $group = null  )
    {
        $src = asset($src);
        $style = '<link rel="stylesheet" type="text/css" class="' . $name . '_style"  href="' . $src . "\" ></link>";
        if(isset($group))
        {
            $group = strtoupper($group);
            if(!isset(self::$specific_enqueue_styles[$group] ))
                self::$specific_enqueue_styles[$group] = [];
            self::$specific_enqueue_styles[$group][$name] = $style;
        }
        else{
            self::$enqueue_styles[$name] = $style;
        }


    }
    public static function get_ajax_manager()
    {
        if(isset(self::$ajax_manager))
        {
            return self::$ajax_manager;
        }
        else
        {
            self::$ajax_manager = new \app\libraries\ajax\AjaxManager();
            return  self::$ajax_manager;
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @param null $group
     */
    public static function enqueue_inline_style( $name,  $value, $group = null )
    {
        $style = '<style type="text/css" class="' . $name . '_style" >' . $value . "</style>";
        if(isset($group))
        {
            $group = strtoupper($group);
            if(!isset(self::$specific_enqueue_styles[$group] ))
            {
                self::$specific_enqueue_styles[$group] = array();
            }
            self::$specific_enqueue_styles[$group][$name] = $style;
        }
        else{
            self::$enqueue_styles[$name] = $style;
        }

    }




    public static function header($group = null)
    {
        $response = "";
        if(isset($group))
        {
            $group = strtoupper($group);
            if(isset(self::$specific_enqueue_styles[$group]))
            {
                foreach(self::$specific_enqueue_styles[$group] as $style)
                {
                    $response .= $style . "\n";
                }
            }

        }
        else
        {

            foreach(self::$enqueue_styles as $style)
            {
                $response .= $style . "\n";
            }

        }
        return $response;

    }
    public static function footer($group = null)
    {
        $response = "";
        if(isset($group))
        {
            $group = strtoupper($group);
            if(isset(self::$specific_enqueue_scripts[$group]))
            {
                foreach(self::$specific_enqueue_scripts[$group] as $script)
                {
                    $response .= $script  . "\n";
                }
            }

        }
        else{
            foreach(self::$enqueue_scripts as $script)
            {
                $response .= $script  . "\n";
            }
        }

        foreach(self::$footertasks as $footerTask)
        {
            $response .= $footerTask . "\n";
        }

        return $response;
    }

    public static function addFootertask($task)
    {
        array_push(self::$footertasks, $task);
    }
    public static function construct_theme()
    {
        self::$theme = new \app\libraries\extra\themes\defaultTheme\DefaultTheme();
    }
    public static function get_theme_object()
    {
        return self::$theme;
    }
}