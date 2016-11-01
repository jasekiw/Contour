<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/30/2015
 * Time: 11:22 AM
 */

namespace app\libraries\tags\collection;

use TijsVerkoyen\CssToInlineStyles\Exception;

class TagSubClass
{
    
    private $tag = null;
    private $name = null;
    
    /**
     * @param string                      $input_name
     * @param \app\libraries\tags\DataTag $tag
     */
    function __construct($input_name, $tag = null)
    {
        
        $this->tag = $this->isOfType($tag) ? $tag : null;
        $this->name = $input_name;
    }
    
    /**
     * @param $tag
     *
     * @return bool
     */
    private function isOfType($tag)
    {
        if (isset($tag)) {
            if (strpos(get_class($tag), "Tag") > -1) {
                return true;
            }
            return false;
        }
        return false;
    }
    
    /**
     * @return \app\libraries\tags\DataTag
     */
    function get()
    {
        return $this->tag;
    }
    
    /**
     * @param $tag
     *
     * @return bool
     * @throws Exception
     */
    function set($tag)
    {
        if (get_class($tag) == "DataTag") {
            throw new Exception("TagCollection: Cannot set type " . get_class($tag) . " to a " . $this->name . " Tag");
        } else {
            $this->tag = $tag;
            return true;
        }
    }
    
    /**
     * @return bool
     */
    function is_set()
    {
        return isset($this->tag) ? true : false;
    }
}