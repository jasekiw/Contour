<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/22/2015
 * Time: 3:17 PM
 */

namespace app\libraries\extra\themes\defaultTheme;


use app\libraries\theme\ThemeOptions;

class DefaultTheme extends ThemeOptions
{

    private $includes = null;

    public function __construct()
    {
        $this->includes = new Includes();
    }

    public function get_name()
    {
        return "default";
    }
}