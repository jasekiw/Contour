<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:12 AM
 */

namespace app\libraries\tags;

use app\libraries\database\DatabaseObject;
use app\libraries\memory\datablocks\DataBlock;
use app\libraries\memory\tags\TagCollection;
use app\libraries\memory\types\Type;
use app\libraries\types\TypeAbstract;
use Exception;

/**
 * Class DataTagAbstract
 * @package app\libraries\tags
 */
abstract class DataTagAbstract extends DatabaseObject
{
    
    /**
     * @var int
     * @access protected
     */
    protected $id = null;
    /**
     * @var string
     * @access protected
     */
    protected $name = null;
    /**
     * @var Type $type
     * @access protected
     */
    protected $type = null;
    
    /**
     * Constructs a new tag with timestamp arguments
     *
     * @param string $updated_at
     * @param string $created_at
     *
     * @return DataTag
     */
    public static function constructWithTimestamp($updated_at, $created_at)
    {
        $tag = new DataTag();
        $tag->updated_at = $updated_at;
        $tag->created_at = $created_at;
        return $tag;
    }
    
    /**
     * @return DataBlock
     */
    public abstract function create_data_block();
    
    /**
     * @return DataBlock
     */
    public abstract function get_data_block();
    
    /**
     * @return integer
     */
    public function get_id()
    {
        return $this->id;
    }
    
    /**
     * Clone a tag's children into the children of this tag. This is a deep copy
     *
     * @param DataTag $tag    The tag to clone into this tag
     * @param null    $parent Do not use this attribute
     *
     * @throws Exception
     */
    public abstract function clone_children($tag, $parent = null);
    
    /**
     * @return Type
     */
    public abstract function get_type();
    
    /**
     * @param TypeAbstract $type
     */
    public abstract function set_type(TypeAbstract $type);
    
    /**
     * @return string
     */
    public abstract function get_name();
    
    /**
     * Sets the name of the tag. $name cannot be null or be blank
     *
     * @param string $name
     */
    public abstract function set_name($name);
    
    /**
     * @return integer
     */
    public abstract function get_sort_number();
    
    /**
     * @param integer $number
     */
    public abstract function set_sort_number($number);
    
    /**
     * Saves the Tag to the database only if it exists
     * @return bool
     * @throws Exception
     */
    public abstract function save();
    
    /**
     * Finds tags unlimited layers deep
     *
     * @param String $tagname
     *
     * @return TagCollection
     */
    public abstract function find($tagname);
    
    /**
     * Finds a child tag with name == $tagname
     *
     * @param String $tagname
     *
     * @return DataTag
     */
    public abstract function findChild($tagname);
    
    /**
     * Gets a child by the sort number. WARNING: this is a recursive statement and can be slow
     *
     * @param int  $sortnumber
     * @param Type $type
     *
     * @return DataTag
     */
    public abstract function findChildBySortNumber($sortnumber, $type);
    
    /**
     * Creates a new instance of the tag in the database. It will return false if it already exists.
     * @throws Exception
     * @returns bool
     *
     */
    public abstract function create();
    
    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public abstract function forceDelete();
    
    /**
     * Deletes this tag and all children
     */
    public abstract function delete_recursive();
    
    /**
     * Deletes this tag and all children
     */
    public abstract function force_delete_recursive();
    
    /**
     * Gets the immediate parent of this tag. returns null if no parent is found
     * @return DataTag
     */
    public abstract function get_parent();
    
    /**
     * Searches all of the parents of this element to find a parent of this type. Null is returned if none found
     *
     * @param Type $type
     *
     * @return DataTag
     */
    public abstract function get_parent_of_type($type);
    
    /**
     * Gets the ID of the parent tag
     * @return int Returns -1 if the tag has no parent
     */
    public abstract function get_parent_id();
    
    /**
     * Gets the root parent tag.
     * @return DataTag
     */
    public abstract function get_root();
    
    /**
     * Gets the first level children of this tag
     * @return TagCollection
     */
    public abstract function get_children();
    
    /**
     * Gets children That have Children
     * @return TagCollection|null
     */
    public abstract function getCompositChildren();
    
    /**
     * Gets children that do not have Children
     * @return TagCollection|null
     */
    public abstract function getSimpleChildren();
    
    /**
     * Gets all of the children of this tag recursively
     * @return TagCollection
     */
    public abstract function get_children_recursive();
    
    /**
     * Checks to see if this tag has children
     * @return bool Returns true if this tag has children
     */
    public abstract function has_children();
    
    /**
     * Gets the number of layers deep the tag is. This function caches the value after it runs
     * @return int The amount of parents this tag has
     * @throws Exception if the tag's parent_id is not initialized an error is thrown
     */
    public abstract function get_layers_deep();
    
    /**
     * Gets the layers between the tag and the nearest sheet
     * @return int|null
     */
    public abstract function get_layers_deep_to_sheet();
    
    /**
     * Clears the caches tp grab fresh values.
     */
    public abstract function invalidate_caches();
    
    /**
     * Trace stack of parents all the way to root or until the specified tag is met.
     *
     * @param DataTag $tagtoStop
     *
     * @return DataTag[]
     */
    public abstract function getParentTrace($tagtoStop = null);
    
    /**
     * cheks if current tag exists in the database
     * @return bool
     */
    public abstract function exists();
    
    /**
     * Gets the NiceName of the tag. if none found, the name of the tag is given
     * @return mixed|null
     */
    public abstract function getNiceName();
    
    /**
     * Sets the nice name of the tag
     *
     * @param $name
     */
    public abstract function setNiceName($name);
}