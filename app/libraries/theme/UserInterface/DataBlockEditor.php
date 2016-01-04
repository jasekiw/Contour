<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/22/2015
 * Time: 9:15 AM
 */

namespace app\libraries\theme\UserInterface;


/**
 * Class DataBlockEditor
 * @package app\libraries\theme\UserInterface
 */
class DataBlockEditor
{
    /**
     * Gets the datablock editor's html
     * @return string
     */
    public static function get()
    {
        $view = \View::make('datablocks.editor');
        return $view->render();
    }

}