<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/22/2015
 * Time: 3:57 PM
 */

namespace app\libraries\theme;


abstract class ThemeOptions
{

    public abstract function __construct();
    public abstract function get_name();
}