<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 12:04 AM
 */

namespace app\libraries\memory\tags;


class ChangedDataTagsManager
{
    private static $changed_tags = [];

    /**
     * @param DataTag $tag
     */
    public static function addToChanged($tag)
    {
        self::$changed_tags[$tag->get_id()] = $tag;
    }

    /**
     * @param DataTag $tag
     */
    public static function removeFromChanged($tag)
    {
        unset(self::$changed_tags[$tag->get_id()]);
    }

    /**
     * @return array
     */
    public static function getChangedDataTags()
    {
        return self::$changed_tags;
    }

}