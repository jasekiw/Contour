<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/22/2015
 * Time: 1:38 PM
 */

namespace app\libraries\extra\themes\defaultTheme\includes\ajax;

use app\libraries\ajax\AjaxScript;
use Input;
use app\libraries\tags\DataTags;

class AjaxTagController extends AjaxScript
{
    
    public function get($parameters)
    {
        if (isset($parameters[0]) && $parameters[0] == "get_rename_ui") {
            ?>
            
            <?php
        }
    }
    
    public function post($parameters)
    {
        
        if (isset($parameters[0])) {
            $id = $parameters[0];
            $tag = DataTags::get_by_id($id);
            $command = Input::get("command");
            
            if ($command == "rename") {
                
                $name = Input::get("name");
                if (isset($name) && strlen($name) > 0) {
                    
                    $tag->set_name($name);
                    $tag->save();
                    echo $tag->get_name();
                } else {
                    echo $tag->get_name();
                }
            }
        }
    }
    
    public function get_id()
    {
        return "tageditor";
    }
}