<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/21/2015
 * Time: 4:44 PM
 */

namespace app\libraries\ajax;

abstract class AjaxScript
{
    
    public abstract function get($parameters);
    
    public abstract function post($parameters);
    
    public abstract function get_id();
}