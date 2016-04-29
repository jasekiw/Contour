<?php
declare(strict_types=1);
/**
 * @author Jason Gallavin
 * Date: 7/15/2015
 * Time: 1:30 PM
 */

namespace app\libraries\tags;

use app\libraries\database\Query;
use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\collection\TagCollection;
use app\libraries\types\TypeAbstract;
use app\libraries\types\Types;
use App\Models\Tag;
use app\libraries\types\Type;
use App\Models\Tag_meta;
use App\Models\Tags_reference;
use PDO;
use TijsVerkoyen\CssToInlineStyles\Exception;

/**
 * Class DataTag
 * @package app\libraries\tags
 */
class DataTag extends DataTagAbstract
{
    /**
     * @var string
     * @access private
     */
    protected $name = null;
    /**
     * @var Type $type
     * @access private
     */
    protected $type = null;
    /**
     * @var int
     */
    private $type_id = null;
    /**
     * @var int
     * @access private
     */
    private $parent_id = null;
    private $cached_parent = null;
    /**
     * @var int
     * @access private
     */
    private $sort_number = null;
    /**
     * @var int
     * @access private
     */
    private $cached_layers_deep = null;

    private $cached_layers_deep_to_sheet = null;
    /**
     * @var TagCollection
     * @access private
     */
    private $children = null;
    private $cached_metas = [];

    /**
     * Creates a new tag to insert into the database
     * @param String $name
     * @param integer $parent_id
     * @param Type $type
     * @param integer $sort_number
     * @throws Exception
     */
    public function __construct(string $name = null, int $parent_id = null, Type $type = null, int $sort_number = null)
    {
        if($parent_id === -1) //fixes unsigned references
            $parent_id = 0;
        if(isset($name))
            $this->name = DataTags::validate_name($name);
        if(isset($parent_id))
            $this->parent_id = $parent_id;
        if(isset($type))
            $this->set_type($type);
        if(isset($sort_number))
            $this->sort_number = $sort_number;
    }

    /**
     * Constructs a new tag with timestamp arguments
     * @param string $updated_at
     * @param string $created_at
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
    public function create_data_block()
    {
        $type = Types::get_type_datablock("value");
        if(!isset($type))
            $type = Types::create_type_datablock("value");
        $datablock = new DataBlock(new TagCollection( array($this)), $type);
        $datablock->create();
        return $datablock;
    }

    /**
     * @return DataBlock
     */
    public function get_data_block()
    {
        $datablock = DataBlocks::getByTagsArray(array($this));
        return $datablock;
    }


    /**
     * Clone a tag's children into the children of this tag. This is a deep copy
     * @param DataTag $tag The tag to clone into this tag
     * @param null $parent Do not use this attribute
     * @throws Exception
     */
    public function clone_children($tag, $parent = null)
    {
        if($parent == null)
            $parent = $this;
        $children = $tag->get_children()->getAsArray(TagCollection::SORT_TYPE_BY_SORT_NUMBER);
        foreach($children as $child)
        {
            $newDataTag = new DataTag($child->get_name(), $parent->get_id(), $child->get_type(), $child->get_sort_number());
            $newDataTag->create();
            if($child->has_children())
                $this->clone_children($child, $newDataTag);
        }
    }

    /**
     * Gets the first level children of this tag
     * @return TagCollection
     */
    public function get_children()
    {
        if(!isset($this->id))
            return null;
        if(isset($this->children))
            return $this->children;
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();
        if(empty($tagmodels))
            return new TagCollection();
        $collection = new TagCollection();
        foreach($tagmodels as $tagmodel)
            $collection->add( DataTags::get_by_row($tagmodel) );
        $this->children = $collection;
        return $collection;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Sets the name of the tag. $name cannot be null or be blank
     * @param string $name
     */
    public function set_name($name)
    {
        if(isset($name) && strlen($name) > 0)
            $this->name = DataTags::validate_name($name);
    }

    /**
     * @return Type
     */
    public function get_type()
    {
        if(isset($this->type))
            return $this->type;
        $this->type = Types::get_by_id($this->getTypeId());
        return $this->type;
    }

    /**
     * @param TypeAbstract $type
     */
    public function set_type(TypeAbstract $type)
    {
        if(isset($type))
        {
            $this->type = $type;
            $this->type_id = $type->get_id();
        }
        else
            $this->type = null;
    }

    public function getTypeId() : int
    {
        if(isset($this->type_id))
            return $this->type_id;
        $this->type_id = Tag::where('id', '=', $this->id)->first()->type_id;
        return $this->type_id;

    }

    /**
     * @param int $id
     * @return void
     */
    public function set_type_id($id)
    {
        $this->type_id = $id;
        $this->type = null;
        return;
    }

    /**
     * @return integer
     */
    public function get_sort_number() : int
    {
        return $this->sort_number;
    }

    /**
     * @param integer $number
     * @return void
     */
    public function set_sort_number($number)
    {
        if(isset($number))
            $this->sort_number = $number;
        else
            $this->sort_number = -1;
    }

    /**
     * Creates a new instance of the tag in the database. It will return false if it already exists.
     * @throws Exception
     * @returns bool
     *
     */
    public function create() : bool
    {

        if (!isset($this->name))
            throw new Exception("The tag has to have a name before it can be created.");
        if (!isset($this->parent_id))
            throw new Exception("The tag has to have a parent_id before it can be created.");
        if(!isset($this->type))
        {
            $this->type = new Type();
            $this->type->set_id(-1);
        }

        if (isset($this->id))
            return false;

        $tag = new Tag();
        $tag->name = $this->name;
        $tag->type_id = $this->type->get_id();

        $tag->parent_tag_id = $this->parent_id;
        $this->save_sort_number($tag);
        $tag->save();
        $this->id = $tag->id;
        return true;
    }

    /**
     * Saves the sort number to the database
     * @param Tag $tag
     */
    private function save_sort_number($tag)
    {
        if(isset($this->sort_number))
            $tag->sort_number = $this->sort_number;
        else
            $tag->sort_number = -1;
    }

    /**
     * Checks to see if this tag has children
     * @return bool Returns true if this tag has children
     */
    public function has_children() : bool
    {
        if(!isset($this->id))
            return false;
        if(Tag::where('parent_tag_id', '=', $this->id)->exists())
           return true;
        else
           return false;
    }



    /**
     * Saves the Tag to the database only if it exists
     * @return bool
     * @throws Exception
     */
    public function save() : bool
    {
        if(!isset($this->name))
            throw new Exception("The tag has to have a name before it can be saved.");
        if(!isset($this->parent_id))
            throw new Exception("The tag has to have a parent_id before it can be saved.");
        $this->check_parent_id();

        if(isset($this->id))
            $this->save_to_current_tag();
        else
        {
            if($this->check_for_duplicate())
                $this->save_to_current_tag();
            else
                throw new Exception("No database tag to save to. If you want to create a tag, use the create() method");
        }
        return true;
    }

    /**
     * sets parent id to root if there is no parent Id set
     */
    private function check_parent_id()
    {
        if($this->parent_id == -1 || !isset($this->parent_id))
            $this->parent_id  = 0;
    }

    /**
     * Saves the tag to the database
     */
    private function save_to_current_tag()
    {
        $tag = Tag::where("id", "=", $this->id)->first();
        $tag->name = $this->name;
        $tag->type_id = $this->get_type()->get_id();
        $tag->parent_tag_id = $this->parent_id;
        $tag->sort_number = $this->sort_number;
        $tag->save();
    }

    /**
     * Checks the database for another tag of the same
     * @return int|null
     */
    private function check_for_duplicate()
    {
        $tag = Tag::where("parent_tag_id", "=", $this->parent_id )->where("name", "=", $this->name )->first();
        if(isset($tag))
            return $tag->id;
        else
            return null;
    }

    /**
     * Finds tags unlimited layers deep
     * @param String $tagname
     * @return TagCollection
     */
    public function find($tagname)
    {
        $children = $this->get_children()->getAsArray();
        $collection = new TagCollection();
        foreach($children as $child)
        {
            if($child->name == $tagname)
                $collection->add($child);
            $collection->addAll( $child->find($tagname) );
        }
        return $collection;
    }

    /**
     * Finds a child tag with name == $tagname
     * @param String $tagname
     * @return DataTag
     */
    public function findChild($tagname)
    {
        $query = DataTags::getDataTagsQueryBuilder();
        $query->where('parent_tag_id', '=', $this->id)->whereRaw('UPPER(tags.name) = ? ', [strtoupper($tagname)] );
        /** @var \App\Models\Tag[] $tagmodels */
        $tagmodels = $query->get();
        if(empty($tagmodels))
            return null;
        return DataTags::get_by_row($tagmodels[0]);
    }

    /**
     * Gets a child by the sort number. WARNING: this is a recursive statement and can be slow
     * @param int $sortnumber
     * @param Type $type
     * @return DataTag
     */
    public function findChildBySortNumber($sortnumber, $type)
    {
        $children = $this->get_children()->getAsArray(TagCollection::SORT_TYPE_BY_SORT_NUMBER);
        foreach($children as $index => $child)
        {
            if($child->get_type()->getName() != $type->getName()) // is not of right type so we remove it
               unset($children[$index]);
        }

        $children = array_values($children); // reindex the array to not have gaps
        /** @var DataTag[] $children */
        foreach($children as $index => $child)
        {
            if(($index +1) >= sizeof($children)) //next child assignment
                $nextChild = null;
            else
                $nextChild = $children[$index +1];
            if($child->get_sort_number() == $sortnumber )
                return $child;
            // if sort number less than required sort number and this has children, and the next child has a sort number grator than required
            if($child->get_sort_number() < $sortnumber &&  $child->has_children())
            {
                if($nextChild === null) // this is the last child :(
                    return $child->findChildBySortNumber($sortnumber, $type);
                else if($nextChild->get_sort_number() > $sortnumber) // the next child is passed the sort number
                    return $child->findChildBySortNumber($sortnumber, $type);
            }
        }
        return null;
    }

    /**
     * Deletes this tag and all children
     */
    public function delete_recursive()
    {
        foreach($this->get_children_recursive()->getAsArray() as $child)
            $child->delete();
        $this->delete();
    }

    /**
     * Gets all of the children of this tag recursively
     * @return TagCollection
     */
    public function get_children_recursive()
    {
        if(!isset($this->id))
            return null;
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();
        if(empty($tagmodels))
            return new TagCollection();

        $collection = new TagCollection();
        foreach($tagmodels as $tagmodel)
        {
            $tag = DataTags::get_by_row($tagmodel);
            $childCollection = $tag->get_children_recursive();
            $collection->add($tag);
            $collection->addAll($childCollection);
        }
        return $collection;

    }

    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public function delete()
    {
        Tag::where("id", "=", $this->id)->delete();
        Tag_meta::where('tag_id', '=',$this->id )->delete();
        $this->id = null;
    }


    /**
     * Deletes the tag and all associated datablocks
     */
    public function fullDelete()
    {
        Tag::where("id", "=", $this->id)->delete();
        Tag_meta::where('tag_id', '=',$this->id )->delete();
        Tags_reference::where("tag_id", "=",$this->id )->delete();
        $this->id = null;
    }

    /**
     * Deletes this tag and all children
     */
    public function force_delete_recursive()
    {
        foreach($this->get_children_recursive()->getAsArray() as $child)
            $child->forceDelete();
        Tag::where("id", "=", $this->id)->forceDelete();
    }

    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public function forceDelete()
    {
        Tag::where("id", "=", $this->id)->forceDelete();
        $this->id = null;
    }

    /**
     * Searches all of the parents of this element to find a parent of this type. Null is returned if none found
     * @param Type $type
     * @return DataTag
     */
    public function get_parent_of_type($type)
    {
        $searchTypeId = $type->get_id();
        $parentId = $this->get_parent_id();
        while($parentId != 0)
        {
            $query = "SELECT id,parent_tag_id, type_id FROM tags where id = '" . $parentId . "'";
            $row = Query::getPDO()->query($query)->fetch(PDO::FETCH_ASSOC);
            if($row === false)
                return null;
            if($searchTypeId == $row["type_id"])
                return DataTags::get_by_id($row["id"]);

            $parentId = $row["parent_tag_id"];

        }
        return null;
    }

    /**
     * Gets the ID of the parent tag
     * @return int Returns -1 if the tag has no parent
     */
    public function get_parent_id()
    {
        if($this->parent_id !== null)
            return $this->parent_id;
        else
        {
            $sql = "SELECT parent_tag_id FROM tags WHERE id = $this->id";
            $parent_id = Query::getPDO()->query($sql)->fetchColumn(0);
            $this->parent_id = $parent_id;
            return $this->parent_id;
        }

    }

    /**
     * @param integer $id
     */
    public function set_parent_id($id)
    {
        if(isset($id))
            $this->parent_id = $id;
        else
            $this->parent_id = 0;
    }

    /**
     * Gets the root parent tag.
     * @return DataTag
     */
    public function get_root()
    {
        $root = null;
        $parent = $this->get_parent();
        if($parent === null)
            return $this;
        /// if current parent is not null
        while($parent !== null)
        {
            $root = $parent;
            $parent = $parent->get_parent();
        }
        return $root;
    }

    /**
     * Gets the immediate parent of this tag. returns null if no parent is found
     * @return DataTag
     */
    public function get_parent()
    {
        if(isset($this->cached_parent))
            return $this->cached_parent;
        if($this->parent_id === null)
            return null;
        $this->cached_parent = DataTags::get_by_id($this->parent_id);
        return  $this->cached_parent;
    }

    /**
     * Gets children That have Children
     * @return TagCollection|null
     */
    public function getCompositChildren()
    {
        if(!isset($this->id))
            return null;
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();
        $collection = new TagCollection();
        if(empty($tagmodels))
            return $collection;
        foreach($tagmodels as $tagmodel)
            if(Tag::where('parent_tag_id', '=', $tagmodel->id)->exists())
                $collection->add(DataTags::get_by_row($tagmodel));
        $this->children = $collection;
        return $collection;
    }

    /**
     * Gets children that do not have Children
     * @return TagCollection|null
     */
    public function getSimpleChildren()
    {
        if(!isset($this->id))
            return null;
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();
        $collection = new TagCollection();
        if(sizeof($tagmodels) === 0)
            return $collection;
        foreach($tagmodels as $tagmodel)
            if( !Tag::where('parent_tag_id', '=', $tagmodel->id)->exists())
                $collection->add(DataTags::get_by_row($tagmodel));
        $this->children = $collection;
        return $collection;
    }

    /**
     * Gets the number of layers deep the tag is. This function caches the value after it runs
     * @return int The amount of parents this tag has
     * @throws Exception if the tag's parent_id is not initialized an error is thrown
     */
    public function get_layers_deep()
    {
        if(isset($this->cached_layers_deep))
            return $this->cached_layers_deep;

        if($this->get_parent_id() == 0)
            return 0;
        if($this->parent_id !== null)
        {

            $query = "SELECT parent_tag_id FROM tags WHERE id = '" . $this->parent_id . "';";
            $parent = Query::getPDO()->query($query)->fetchColumn(0);
            $count = 0;
            while($parent !== 0)
            {
                $count++;
                $query = "SELECT parent_tag_id FROM tags WHERE id = '" . $parent . "';";
                $parent = Query::getPDO()->query($query)->fetchColumn(0);
            }
            $this->cached_layers_deep = $count;
            return $count;
        }
        else
            throw new Exception('The Tag has to be initialized first. parent_id is null.');

    }

    /**
     * Gets the layers between the tag and the nearest sheet
     * @return int|null
     */
    public function get_layers_deep_to_sheet()
    {
        if(isset($this->cached_layers_deep_to_sheet))
            return $this->cached_layers_deep_to_sheet;
        $count = 0;
        $type_name = "";
        $parent = $this->get_parent();
        if(isset($parent))
        {
            $type_name = $parent->get_type()->getName();
            if($type_name == "sheet")
                $count = 1;
        }
        while($parent !== null &&  $type_name != "sheet" )
        {
            $count++;
            $parent = $parent->get_parent();
            if(isset($parent))
                $type_name = $parent->get_type()->getName();
        }
        $this->cached_layers_deep_to_sheet = $count;
        return $count;
    }

    /**
     * Clears the caches tp grab fresh values.
     */
    public function invalidate_caches()
    {
        $this->cached_layers_deep = null;
        $this->updated_at = null;
        $this->created_at = null;
    }

    /**
     * Gets the date at when the object was updated
     * @return String
     */
    public function updated_at()
    {
        if(isset($this->updated_at)) // cached
            return $this->updated_at;
        if(!isset($this->id))
            return null;
        /**@var Tag $tag */
        $tag = Tag::where('id', '=', $this->id)->first();
        $updated_at = $tag->updated_at;
        $this->updated_at = $updated_at;
        return $updated_at;
    }

    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if(isset($this->created_at)) // cached
            return $this->created_at;
        if(!isset($this->id))
            return null;
        /** @var Tag $tag */
        $tag = Tag::where('id', '=', $this->id)->first();
        $created_at = $tag->created_at;
        $this->created_at = $created_at;
        return $created_at;
    }

    /**
     * Trace stack of parents all the way to root or until the specified tag is met.
     * @param DataTag $tagtoStop
     * @return DataTag[]
     */
    public function getParentTrace($tagtoStop = null)
    {
        $idToStop = isset($tagtoStop) ? $tagtoStop->get_id() : -1;
        $parent =  $this->get_parent();
        $stack = array();
        while($parent !== null)
        {
            if($parent->get_id() != $idToStop)
            {
                $stack[] = $parent;
                $parent =  $parent->get_parent();
            }
            else
                break;
        }
        $newstack = array(); //reversing the order
        $stackSize = sizeof($stack);
        for($i = $stackSize - 1; $i >= 0; $i--)
            $newstack[$stackSize - ($i + 1) ] = $stack[$i];
        return $newstack;
    }

    /**
     * cheks if current tag exists in the database
     * @return bool
     */
    public function exists()
    {
        if(isset($this->id))
            return Tag::where("id", "=", $this->id)->exists();
        if(isset($this->name) && isset($this->parent_id))
            return  DataTags::get_by_string($this->name,$this->parent_id ) ? true : false;
        return false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getMetaValue($name)
    {
        $meta = Tag_meta::where('tag_id', '=', $this->get_id())->where('name', '=', $name)->first();
        if(isset($meta))
            return $meta->value;
        return "";
    }
    public function setMetaValue($name, $value)
    {
        $meta = Tag_meta::where('tag_id', '=', $this->get_id())->where('name', '=', $name)->first();
        if(isset($meta))
        {
            $meta->value = $value;
            $meta->save();
            return;
        }
        $meta = new Tag_meta();
        $meta->name = $name;
        $meta->value = $value;
        $meta->tag_id = $this->id;
        $meta->save();
    }

    /**
     * Gets the NiceName of the tag. if none found, the name of the tag is given
     * @return mixed|null
     */
    public function getNiceName()
    {
        if(isset($this->cached_metas['nice_name']))
            return $this->cached_metas['nice_name'];
        /** @var \App\Models\Tag_meta $nice_name_row */
        $nice_name_row = Tag_meta::where('tag_id', '=', $this->get_id())->where('name', '=', 'nice_name')->first();
        if(!isset($nice_name_row))
        {
            $nice_name_row = new Tag_meta();
            $nice_name_row->tag_id = $this->get_id();
            $nice_name_row->name = 'nice_name';
            $nice_name_row->value = $this->get_name();
            $nice_name_row->save();
            return $nice_name_row->value;
        }
        return $nice_name_row->value;
    }

    /**
     * Sets the nice name of the tag
     * @param $name
     */
    public function setNiceName($name)
    {
        /** @var \App\Models\Tag_meta $nice_name_row */
        $nice_name_row = Tag_meta::where('tag_id', '=', $this->get_id())->where('name', '=', 'nice_name')->first();
        if(!isset($nice_name_row))
        {
            $nice_name_row = new Tag_meta();
            $nice_name_row->tag_id = $this->get_id();
            $nice_name_row->name = 'nice_name';
            $nice_name_row->value = $name;
            $nice_name_row->save();
        }
        else
        {
            $nice_name_row->value = $name;
            $nice_name_row->save();
        }
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
        $std->typeId = $this->type_id;
        $std->type = $this->get_type()->getName();
        $std->parentId = $this->parent_id;
        return $std;
    }
}