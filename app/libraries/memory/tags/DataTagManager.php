<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 12:48 PM
 */

namespace app\libraries\memory\tags;


use app\libraries\memory\MemoryDataManager;
use app\libraries\memory\types\Type;
use app\libraries\tags\DataTagManagerAbstract;
use app\libraries\tags\DataTags;

/**
 * Class DataTagManager
 * @package app\libraries\memory\tags
 */
class DataTagManager extends DataTagManagerAbstract
{
    private $manager = null;



    /**
     * DataTagManager constructor.
     * @param MemoryDataManager $manager
     */
    function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Gets a datatag object by the row id. return null if none found.
     * @param int $id
     * @param bool $showTrashed Not Implemented
     * @return DataTag
     */
    public function get_by_id($id, $showTrashed = false)
    {
        return $this->manager->tags[$id];
    }

    /**
     * Gets a TagCollection of all the tags with the given parent id
     * @param int $id
     * @return TagCollection
     */
    public function get_by_parent_id($id)
    {
        return new TagCollection($this->manager->tagsByParentId[$id]);
    }

    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @param integer $parent_id Optional
     * @param bool $showTrashed Not Implemented
     * @return DataTag
     */
    public function get_by_string( $text, $parent_id = null, $showTrashed = false)
    {
        if(isset($parent_id))
        {
            if($parent_id == -1)
                return isset($this->manager->tagsByNameAndParentId[$text][0]) ? $this->manager->tagsByNameAndParentId[$text][0] : null;
            else
                return isset($this->manager->tagsByNameAndParentId[$text][$parent_id]) ? $this->manager->tagsByNameAndParentId[$text][$parent_id] : null;
        }
        return new TagCollection($this->manager->tagsByName[$text]);
    }

    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @return DataTag[]
     */
    public function get_multiple_by_string( $text)
    {
        if(isset($this->manager->tagsByName[$text]))
            return new TagCollection($this->manager->tagsByName[$text]);
        return new TagCollection();
    }

    /**
     * Removes unworthy characters from the tag Identifier
     * @param string $name The string to validate
     * @return string
     */
    public  function validate_name($name)
    {
        return DataTags::validate_name($name);
    }

    /**
     * Use this function only if you do not have the datatag object. This is slower than calling findChildBySortNumber
     * @param integer $sort_number
     * @param Type $type
     * @param integer $parent_id
     * @return DataTag Will also return null if nothing found
     */
    public  function get_by_sort_id( $sort_number,  $type,  $parent_id)
    {
        $parent = $this->manager->tags[$parent_id];
        $datatag = $parent->findChildBySortNumber($sort_number, $type);
        return $datatag;
    }

    /**
     * @param String|integer $nameorID
     * @param integer|null $parentID
     * @return bool Exists
     */
    public function exists($nameorID, $parentID = null)
    {
        if(is_int($nameorID))
            return isset($this->manager->tags[$nameorID]);
        if(!isset($parentID))
            return false;
        if(isset($this->manager->tagsByNameAndParentId[$nameorID][$parentID]))
            return true;
        return false;
    }

    /**
     * @param string $text
     * @param Type $type
     * @param int $parent_id
     * @return DataTag|null
     */
    public function get_by_string_and_type($text, $type, $parent_id = null)
    {
        if(!isset($this->manager->tagsByNameAndParentId[$text]))
            return null;
        if(isset($parent_id))
        {
            if(!isset($this->manager->tagsByNameAndParentId[$text][$parent_id]))
                return null;
            /** @var DataTag $datatag */
            $datatag = $this->manager->tagsByNameAndParentId[$text][$parent_id];
            if($datatag->get_type()->get_id() == $type->get_id())
                return $datatag;
            return null;
        }
        /** @var DataTag[] $tags */
        $tags = $this->manager->tagsByName[$text];
        foreach($tags as $tag)
            if($tag->get_type()->get_id() == $type->get_id())
                return $tag;
        return null;
    }
}