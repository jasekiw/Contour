<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/22/2015
 * Time: 3:47 PM
 */

namespace app\libraries\extra\themes\defaultTheme;


use Theme;

/**
 * Class Includes
 * @package app\libraries\extra\themes\defaultTheme
 */
class Includes
{

    /**
     * Includes constructor.
     */
    function __construct()
    {
        $this->run();
    }
    public function run()
    {
        Theme::get_ajax_manager()->add_script(new AjaxTagController());
    }

}