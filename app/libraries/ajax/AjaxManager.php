<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/21/2015
 * Time: 4:40 PM
 */

namespace app\libraries\ajax;

class AjaxManager
{
    
    private $url = "ajax";
    /** @var AjaxScript[] array   */
    private $scripts = [];
    
    public function get_url()
    {
        return $this->url;
    }
    
    /**
     * @param AjaxScript $script
     */
    public function add_script($script)
    {
        $this->scripts[$script->get_id()] = $script;
    }
    
    public function remove_script($id)
    {
        unset($this->scripts[$id]);
    }
    
    public function call_script_get($id, $parameters)
    {
        if (isset($this->scripts[$id])) {
            $this->scripts[$id]->get($parameters);
        }
    }
    
    public function call_script_post($id, $parameters)
    {
        if (isset($this->scripts[$id])) {
            $this->scripts[$id]->post($parameters);
        }
    }
    
}


