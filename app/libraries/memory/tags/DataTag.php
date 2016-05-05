<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:19 AM
 */

namespace app\libraries\memory\tags;

use app\libraries\memory\datablocks\DataBlock;
use app\libraries\memory\MemoryDataManager;
use app\libraries\memory\types\Type;
use app\libraries\tags\DataTagAbstract;
use app\libraries\types\TypeAbstract;
use Exception;

class DataTag extends DataTagAbstract
{
    
    private $changedProperties = [];
    /** @var int   */
    private $sortnumber;
    /** @var bool   */
    private $changed = false;
    /** @var DataTag   */
    private $parent = null;
    private $children = null;
    
    private $parent_id_for_constucting_only = null;
    
    /**
     * DataTagAbstract constructor.
     *
     * @param int            $id
     * @param string         $name
     * @param DataTag | null $parent
     * @param Type           $type
     * @param int            $sort_number
     * @param string         $updated_at
     * @param string         $created_at
     */
    public function __construct($id = null, $name = null, $parent, $type = null, $sort_number = null, $updated_at = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        if (is_int($parent))
            $this->parent_id_for_constucting_only = $parent;
        else
            $this->parent = $parent;
        $this->type = $type;
        $this->sortnumber = $sort_number;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->children = new TagCollection();
    }
    
    /**
     * Adds the parent To the DataTag without triggering a propertyChangedEvent
     *
     * @param DataTag $tag
     * @param DataTag $parent
     */
    public static function addParentSafetly($tag, $parent)
    {
        $tag->parent = $parent;
    }
    
    /**
     * returns the parent ID That will be used to finish making the object
     *
     * @param DataTag $tag
     *
     * @return mixed
     */
    public static function getParentIdForConstruction($tag)
    {
        return $tag->parent_id_for_constucting_only;
    }
    
    /**
     * Adds a child into the pool of children without effecting save conditions
     *
     * @param DataTag $tag
     * @param DataTag $child
     */
    public static function addChildSafetly($tag, $child)
    {
        $tag->children->add($child);
    }
    
    /**
     * @return DataBlock
     */
    public function create_data_block()
    {
        // TODO: Implement create_data_block() method.
    }
    
    /**
     * @return DataBlock
     */
    public function get_data_block()
    {
        if (isset(MemoryDataManager::getInstance()->referencesByTagId[$this->id][0]))
            return MemoryDataManager::getInstance()->referencesByTagId[$this->id][0];
        return null;
    }
    
    /**
     * Clone a tag's children into the children of this tag. This is a deep copy
     *
     * @param \app\libraries\tags\DataTag $tag    The tag to clone into this tag
     * @param null                        $parent Do not use this attribute
     */
    public function clone_children($tag, $parent = null)
    {
        // TODO: Implement clone_children() method.
    }
    
    /**
     * @return Type
     */
    public function get_type()
    {
        return $this->type;
    }
    
    /**
     * @return integer
     */
    public function get_sort_number()
    {
        return $this->sortnumber;
    }
    
    /**
     * @param integer $number
     */
    public function set_sort_number($number)
    {
        $this->sortnumber = $number;
        $this->setChanged("sort_number");
    }
    
    /**
     * Sets a property as changed
     *
     * @param $property
     */
    private function setChanged($property)
    {
        if (!$this->changed) {
            $this->changed = true;
        }
        $this->changedProperties[$property] = $property;
    }
    
    /**
     * Sets the sql table id
     *
     * @param $id
     */
    public function set_id($id)
    {
        $this->id = $id;
        $this->setChanged("id");
    }
    
    /**
     * Sets the name of the tag. $name cannot be null or be blank
     *
     * @param string $name
     */
    public function set_name($name)
    {
        $this->name = $name;
        $this->setChanged("name");
    }
    
    /**
     * @param TypeAbstract $type
     */
    public function set_type(TypeAbstract $type)
    {
        $this->type = $type;
    }
    
    /**
     * Saves the Tag to the database only if it exists
     * @return bool
     */
    public function save()
    {
        ChangedDataTagsManager::addToChanged($this);
    }
    
    /**
     * Finds tags unlimited layers deep
     *
     * @param String $tagname
     *
     * @return TagCollection
     */
    public function find($tagname)
    {
        $tagCollection = new TagCollection();
        $children = $this->get_children()->getAsArray();
        foreach ($children as $child) {
            if ($child->get_name() == $tagname)
                $tagCollection->add($child);
            $tagCollection->addAll($child->find($tagname));
        }
        return new TagCollection();
    }
    
    /**
     * Gets the first level children of this tag
     * @return TagCollection
     */
    public function get_children()
    {
        // TODO: Implement get_children() method.
    }
    
    /**
     * Finds a child tag with name == $tagname
     *
     * @param String $tagname
     *
     * @return \app\libraries\tags\DataTag
     */
    public function findChild($tagname)
    {
        return $this->children->get($tagname);
    }
    
    /**
     * Gets a child by the sort number. WARNING: this is a recursive statement and can be slow
     *
     * @param int  $sortnumber
     * @param Type $type
     *
     * @return \app\libraries\tags\DataTag
     */
    public function findChildBySortNumber($sortnumber, $type)
    {
        foreach ($this->children->getAsArray() as $child)
            if ($child->get_type()->get_id() == $type->get_id() && $child->get_sort_number() == $sortnumber)
                return $child;
        return null;
    }
    
    /**
     * Creates a new instance of the tag in the database. It will return false if it already exists.
     * @throws Exception
     * @returns bool
     *
     */
    public function create()
    {
        // TODO: Implement create() method.
    }
    
    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }
    
    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public function forceDelete()
    {
        // TODO: Implement forceDelete() method.
    }
    
    /**
     * Deletes this tag and all children
     */
    public function delete_recursive()
    {
        // TODO: Implement delete_recursive() method.
    }
    
    /**
     * Deletes this tag and all children
     */
    public function force_delete_recursive()
    {
        // TODO: Implement force_delete_recursive() method.
    }
    
    /**
     * Gets the immediate parent of this tag. returns null if no parent is found
     * @return \app\libraries\tags\DataTag
     */
    public function get_parent()
    {
        return $this->parent;
    }
    
    /**
     * Searches all of the parents of this element to find a parent of this type. Null is returned if none found
     *
     * @param Type $type
     *
     * @return \app\libraries\tags\DataTag
     */
    public function get_parent_of_type($type)
    {
        if ($this->parent->type->get_id() == $type->get_id())
            return $this->parent;
        $parent = $this->parent;
        while ($parent != null) {
            if ($parent->type->get_id() == $type->get_id())
                return $parent;
            $parent = $parent->parent;
        }
        return null;
    }
    
    /**
     * Gets the ID of the parent tag
     * @return int Returns -1 if the tag has no parent
     */
    public function get_parent_id()
    {
        if (isset($this->parent))
            return $this->parent->id;
        return 0;
    }
    
    /**
     * Gets the root parent tag.
     * @return \app\libraries\tags\DataTag
     */
    public function get_root()
    {
        // TODO: Implement get_root() method.
    }
    
    /**
     * Gets children That have Children
     * @return TagCollection|null
     */
    public function getCompositChildren()
    {
        // TODO: Implement getCompositChildren() method.
    }
    
    /**
     * Gets children that do not have Children
     * @return TagCollection|null
     */
    public function getSimpleChildren()
    {
        // TODO: Implement getSimpleChildren() method.
    }
    
    /**
     * Gets all of the children of this tag recursively
     * @return TagCollection
     */
    public function get_children_recursive()
    {
        // TODO: Implement get_children_recursive() method.
    }
    
    /**
     * Checks to see if this tag has children
     * @return bool Returns true if this tag has children
     */
    public function has_children()
    {
        // TODO: Implement has_children() method.
    }
    
    /**
     * Gets the number of layers deep the tag is. This function caches the value after it runs
     * @return int The amount of parents this tag has
     * @throws Exception if the tag's parent_id is not initialized an error is thrown
     */
    public function get_layers_deep()
    {
        // TODO: Implement get_layers_deep() method.
    }
    
    /**
     * Gets the layers between the tag and the nearest sheet
     * @return int|null
     */
    public function get_layers_deep_to_sheet()
    {
        // TODO: Implement get_layers_deep_to_sheet() method.
    }
    
    /**
     * Clears the caches tp grab fresh values.
     */
    public function invalidate_caches()
    {
        // TODO: Implement invalidate_caches() method.
    }
    
    /**
     * Gets the date at when the object was updated
     * @return String
     */
    public function updated_at()
    {
        // TODO: Implement updated_at() method.
    }
    
    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        // TODO: Implement created_at() method.
    }
    
    /**
     * Trace stack of parents all the way to root or until the specified tag is met.
     *
     * @param \app\libraries\tags\DataTag $tagtoStop
     *
     * @return \app\libraries\tags\DataTag[]
     */
    public function getParentTrace($tagtoStop = null)
    {
        // TODO: Implement getParentTrace() method.
    }
    
    /**
     * cheks if current tag exists in the database
     * @return bool
     */
    public function exists()
    {
        // TODO: Implement exists() method.
    }
    
    /**
     * Gets the NiceName of the tag. if none found, the name of the tag is given
     * @return mixed|null
     */
    public function getNiceName()
    {
        // TODO: Implement getNiceName() method.
    }
    
    /**
     * Sets the nice name of the tag
     *
     * @param $name
     */
    public function setNiceName($name)
    {
        // TODO: Implement setNiceName() method.
    }
    
    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    public function toStdClass()
    {
        $std = new \stdClass();
        $std->name = $this->get_name();
        $std->id = $this->get_id();
        $std->typeId = isset($this->type) ? $this->type->get_id() : -1;
        $std->parentId = isset($this->parent) ? $this->parent->get_id() : -1;
        return $std;
    }
    
    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
}