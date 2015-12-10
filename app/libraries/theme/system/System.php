<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/21/2015
 * Time: 9:41 AM
 */

namespace app\libraries\theme\system;


use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;

class System
{
    private static $cachedSystemTag = null;

    public static function get_system_tag()
    {
        if(isset($cachedSystemTag))
        {
            return self::$cachedSystemTag;
        }
        $system_tag = DataTags::get_by_string("system", -1);
        if(isset($system_tag))
        {
            self::$cachedSystemTag = $system_tag;
            return $system_tag;
        }
        else // not found. something is seriously wrong
        {
            $dataTag = new DataTag("system", -1, Types::get_type_folder());
            $dataTag->set_sort_number(1);
            $dataTag->create();
            self::$cachedSystemTag = $dataTag;
            return $dataTag;
        }
    }

}