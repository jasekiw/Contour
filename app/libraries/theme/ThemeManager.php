<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 9:07 PM
 */

namespace app\libraries\theme;

use app\libraries\ajax\AjaxManager;
use app\libraries\extra\themes\defaultTheme\DefaultTheme;
use app\libraries\theme\menu\MenuManager;

/**
 * Class ThemeManager
 * @package app\libraries\theme
 */
class ThemeManager
{
    
    private $enqueue_scripts = [];
    private $enqueue_styles = [];
    private $specific_enqueue_scripts = [];
    private $specific_enqueue_styles = [];
    private $footertasks = [];
    private $menu = null;
    private $ajax_manager = null;
    private $theme = null;
    
    /**
     * @requires $name is not null. $src is not null
     * @param string $name  The name of the script
     * @param string $src   the location of the script to load relative to the public foler. do not include public. ex
     *                      'assets/js'
     * @param string $group The title of the page to enqueue this script to. the title tag is represented by
     *                      $view->title
     */
    public function enqueueScript($name, $src, $group = null)
    {
        $src = asset($src);
        $script = '<script type="text/javascript" class="' . $name . '_script" src="' . $src . '" ></script>';
        
        if (isset($group)) {
            $group = strtoupper($group);
            if (!isset($this->specific_enqueue_scripts[$group])) {
                $this->specific_enqueue_scripts[$group] = [];
            }
            $this->specific_enqueue_scripts[$group][$name] = $script;
        } else {
            
            $this->enqueue_scripts[$name] = $script;
        }
    }
    
    /**
     * Returns the theme's menu object
     */
    public function getMenuManager()
    {
        if (!isset($this->menu)) {
            $this->menu = new MenuManager();
        }
        
        return $this->menu;
    }
    
    /**
     *  Adds an inline script just below the </body>
     *
     * @param string $name  The class to be added to the inline script tag
     * @param string $value The javascript to add. not including the script tag
     * @param String $group The group or page to add the script to
     */
    public function enqueueInlineScript($name, $value, $group = null)
    {
        $script = '<script type="text/javascript" class="' . $name . '_script">' . $value . "</script>";
        if (isset($group)) {
            $group = strtoupper($group);
            if (!isset($this->specific_enqueue_scripts[$group])) {
                $this->specific_enqueue_scripts[$group] = [];
            }
            $this->specific_enqueue_scripts[$group][$name] = $script;
        } else {
            
            $this->enqueue_scripts[$name] = $script;
        }
    }
    
    /**
     * @param string $name  The class name to add to the style tag
     * @param string $src   The location of the stylesheet to be added. relative to the public folder
     * @param null   $group The group or page to add the script to
     */
    public function enqueueStyle($name, $src, $group = null)
    {
        $src = asset($src);
        $style = '<link rel="stylesheet" type="text/css" class="' . $name . '_style"  href="' . $src . "\" ></link>";
        if (!isset($group)) {
            $group = strtoupper($group);
            if (isset($this->specific_enqueue_styles[$group])) {
                $this->specific_enqueue_styles[$group] = [];
            }
            $this->specific_enqueue_styles[$group][$name] = $style;
        } else {
            $this->enqueue_styles[$name] = $style;
        }
    }
    
    /**
     * Gets the ajax Manager to handle ajax commands
     * @return AjaxManager
     */
    public function getAjaxManager()
    {
        if (isset($this->ajax_manager)) {
            return $this->ajax_manager;
        } else {
            $this->ajax_manager = new AjaxManager();
            return $this->ajax_manager;
        }
    }
    
    /**
     * @param string $name  The class name to add to the style tag
     * @param string $value The css to be added
     * @param null   $group The page pr group to enqueue the style to
     */
    public function enqueueInlineStyle($name, $value, $group = null)
    {
        $style = '<style type="text/css" class="' . $name . '_style" >' . $value . "</style>";
        if (isset($group)) {
            $group = strtoupper($group);
            if (!isset($this->specific_enqueue_styles[$group])) {
                $this->specific_enqueue_styles[$group] = [];
            }
            $this->specific_enqueue_styles[$group][$name] = $style;
        } else {
            $this->enqueue_styles[$name] = $style;
        }
    }
    
    /**
     * To be called right before </header> in the theme. $group is optional
     *
     * @param String|null $group
     *
     * @return string
     */
    public function head($group = null)
    {
        $response = "";
        if (isset($group)) {
            $group = strtoupper($group);
            if (isset($this->specific_enqueue_styles[$group])) {
                foreach ($this->specific_enqueue_styles[$group] as $style) {
                    $response .= $style . "\n";
                }
            }
        } else {
            
            foreach ($this->enqueue_styles as $style) {
                $response .= $style . "\n";
            }
        }
        return $response;
    }
    
    /**
     * To be called right after </body>. $group is optional
     *
     * @param String|null $group
     *
     * @return string
     */
    public function footer($group = null)
    {
        $response = "";
        if (isset($group)) {
            $group = strtoupper($group);
            if (isset($this->specific_enqueue_scripts[$group])) {
                foreach ($this->specific_enqueue_scripts[$group] as $script) {
                    $response .= $script . "\n";
                }
            }
        } else {
            foreach ($this->enqueue_scripts as $script) {
                $response .= $script . "\n";
            }
        }
        
        foreach ($this->footertasks as $footerTask) {
            $response .= $footerTask . "\n";
        }
        
        return $response;
    }
    
    /**
     * Add Html to be added right at the </body>
     *
     * @param String $task
     */
    public function addFootertask($task)
    {
        array_push($this->footertasks, $task);
    }
    
    /**
     * Creates A new Instance of the selected Theme
     */
    public function constructTheme()
    {
        $this->theme = new DefaultTheme();
    }
    
    /**
     * @return DefaultTheme
     */
    public function getCurrentTheme()
    {
        return $this->theme;
    }
}