<?php
/**
 * @author Jason Gallavin
 * Date: 7/15/2015
 * Time: 1:30 PM
 */

namespace app\libraries\tags;

use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\collection\TagCollection;
use app\libraries\types\Types;
use App\Models\Tag;
use app\libraries\database\DatabaseObject;
use app\libraries\types\Type;
use TijsVerkoyen\CssToInlineStyles\Exception;

/**
 * Class DataTag
 * @package app\libraries\tags
 */
class DataTag extends DatabaseObject
{

    /**
     * @var string
     * @access private
     */
    private $name = null;
    /**
     * @var Type $type
     * @access private
     */
    private $type = null;
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


    /**
     * Creates a new tag to insert into the database
     * @param String $name
     * @param integer $parent_id
     * @param Type $type
     * @param integer $sort_number
     * @throws Exception
     */
    public function __construct($name = null, $parent_id = null, $type = null, $sort_number = null)
    {


        //$this->process_argument($type, array("string", "integer"), "type");
        if(isset($name))
        {
            $this->name = str_replace(" ", "_", $name);
        }
        if(isset($parent_id))
        {
            $this->parent_id = $parent_id;
        }
        if(isset($type))
        {
            $this->set_type($type);
        }
        if(isset($sort_number))
        {
            $this->sort_number = $sort_number;
        }





    }


    /**
     * @return DataBlock
     */
    public function create_data_block()
    {
        $type = Types::get_type_datablock("value");
        if(!isset($type))
        {
            $type = Types::create_type_datablock("value");
        }
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
     * @return integer
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return Type
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function get_sort_number()
    {
        return $this->sort_number;
    }


    /**
     * Sets the sql table id
     * @param $id
     */
    public function set_id($id)
    {
        $this->id = $id;

    }


    /**
     * @param string $name
     */
    public function set_name($name)
    {
        if(isset($name))
        {
            $this->name = str_replace(" ", "_", $name);
        }
    }

    /**
     * @param Type $type
     */
    public function set_type($type)
    {
        if(isset($type))
        {
            $this->type = $type;
        }
        else
        {
            $this->type = null;
        }

    }

    /**
     * @param integer $id
     */
    public function set_parent_id($id)
    {
        if(isset($id))
        {
            $this->parent_id = $id;
        }
        else{
            $this->parent_id = -1;
        }


    }

    /**
     * @param integer $number
     */
    public function set_sort_number($number)
    {
        if(isset($number))
        {
            $this->sort_number = $number;
        }
        else
        {
            $this->sort_number = -1;
        }

    }





    /**
     * Saves the Tag to the database only if it exists
     * @return bool
     * @throws Exception
     */
    public function save()
    {

        if(!isset($this->name))
        {
            throw new Exception("The tag has to have a name before it can be saved.");
        }
        if(!isset($this->parent_id))
        {
            throw new Exception("The tag has to have a parent_id before it can be saved.");
        }

        $this->check_parent_id();




        if(isset($this->id))
        {
            $this->save_to_current_tag();
        }
        else
        {
            if($this->check_for_duplicate())
            {
                $this->save_to_current_tag();
            }
            else{

                throw new Exception("No database tag to save to. If you want to create a tag, use the create() method");
            }


        }
        return true;

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
            {
                $collection->add($child);
            }
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
        $children = $this->get_children()->getAsArray();
        foreach($children as $child)
        {
            if(strtoupper($child->name) == strtoupper($tagname))
            {
               return $child;
            }

        }

        return null;
    }



    /**
     * @param int $sortnumber
     * @param Type $type
     * @return DataTag
     */
    public function findChildBySortNumber($sortnumber, $type)
    {
        $children = $this->get_children();
        $children = $children->getAsArray();
        foreach($children as $child)
        {
            if($child->get_type()->getName() == $type->getName()) // is of right type
            {
                if($child->get_sort_number() <= $sortnumber && $child->findChildBySortNumber($sortnumber, $type) != null)
                {
                    return $child->findChildBySortNumber($sortnumber, $type);
                }
                else if($child->get_sort_number() == $sortnumber  )
                {
                    return $child;
                }
            }

        }

        return null;
    }







    /**
     * Saves the tag to the database
     */
    private function save_to_current_tag()
    {
        $tag = Tag::where("id", "=", $this->id)->first();
        $tag->name = $this->name;
        $tag->type_id = $this->type->get_id();
        $tag->parent_tag_id = $this->parent_id;
        $tag->sort_number = $this->sort_number;
        $tag->save();
    }

    /**
     * Saves the sort number to the database
     * @param Tag $tag
     */
    private function save_sort_number($tag)
    {
        if(isset($this->sort_number))
        {
            $tag->sort_number = $this->sort_number;
        }
        else
        {
            $tag->sort_number = -1;
        }

    }


    /**
     *
     * Creates a new instance of the tag in the database. It will return false if it already exists.
     * @throws Exception
     * @returns bool
     *
     */
    public function create()
    {

        if (!isset($this->name)) {
            throw new Exception("The tag has to have a name before it can be created.");
        }

        if (!isset($this->parent_id)) {
            throw new Exception("The tag has to have a parent_id before it can be created.");
        }


        //$this->check_parent_id();
//        $this->check_type_category();
//        $this->check_sort_number();
        //$this->id = $this->check_for_duplicate();
        if(!isset($this->type))
        {
            $this->type = new Type();
            $this->type->set_id(-1);
        }

        if (isset($this->id)) {
            return false;
        }
        else
        {
            $tag = new Tag();
            $tag->name = $this->name;
            $tag->type_id = $this->type->get_id();

            $tag->parent_tag_id = $this->parent_id;
            $this->save_sort_number($tag);
            $tag->save();
            $this->id = $tag->id;
            return true;

        }
    }

    /**
     * Checks the database for another tag of the same
     * @return int|null
     */
    private function check_for_duplicate()
    {
        $tag = Tag::where("parent_tag_id", "=", $this->parent_id )->where("name", "=", $this->name )->first();
        if(isset($tag))
        {
            return $tag->id;
        }
        else
        {
            return null;
        }
    }


    /**
     * @throws Exception
     */
    private function check_parent_id()
    {
        if($this->parent_id == -1 || !isset($this->parent_id))
        {
            $this->parent_id  = -1;
            return;
        }
//        $parent = DataTags::get_by_id($this->parent_id);
//        if(!isset( $parent ) )
//        {
//            throw new Exception("No parent is associated with that parent_id: " . $this->parent_id);
//        }

    }


    /**
     * Warning be sure to check if the tag has children or they will be lost
     * @throws \Exception
     */
    public function delete()
    {
        Tag::where("id", "=", $this->id)->delete();
        $this->id = null;

    }

    /**
     * Deletes this tag and all children
     */
    public function delete_recursive()
    {
        Tag::where("id", "=", $this->id)->delete();
        foreach($this->get_children_recursive()->getAsArray() as $child)
        {
            $child->delete();
        }
    }

    /**
     * Gets the immediate parent of this tag. returns null if no parent is found
     * @return DataTag
     */
    public function get_parent()
    {
        if(isset($this->cached_parent))
        {
            return $this->cached_parent;
        }

        if($this->parent_id != null)
        {
            $this->cached_parent = DataTags::get_by_id($this->parent_id);
            return  $this->cached_parent;
        }
        else
        {
            return null;
        }
    }

    /**
     * Searches all of the parents of this element to find a parent of this type. Null is returned if none found
     * @param Type $type
     * @return DataTag
     */
    public function get_a_parent_of_type($type)
    {
        $parent = $this->get_parent();
        if(isset($parent))
        {
            if($parent->get_type()->get_id() == $type->get_id())
            {
                return $parent;
            }
            else
            {
                return $parent->get_a_parent_of_type($type);
            }
        }
        else
        {
            return null;
        }
    }


    /**
     * Gets the ID of the parent tag
     * @return int Returns -1 if the tag has no parent
     */
    public function get_parent_id()
    {
        if($this->parent_id != null)
        {
            return $this->parent_id;
        }
        else
        {
            return -1;
        }
    }

    /**
     * Gets the root parent tag.
     * @return DataTag
     */
    public function get_root()
    {
        $root = null;
        $parent = $this->get_parent();

        if($parent == null)
        {
            return $this;
        }
        else /// if current parent is not null
        {
            while($parent != null)
            {
                $root = $parent;
                $parent = $parent->get_parent();
            }
            return $root;
        }

    }

    /**
     * Gets the first level children of this tag
     * @return TagCollection
     */
    public function get_children()
    {
        if(!isset($this->id))
        {
            return null;
        }
        if(isset($this->children))
        {
            return $this->children;
        }
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();

        if(sizeOf($tagmodels) != 0)
        {
            $collection = new TagCollection();
            foreach($tagmodels as $tagmodel)
            {
                $tag = DataTags::get_by_row($tagmodel);
                $collection->add($tag);
            }
            $this->children = $collection;
            return $collection;
        }
        else
        {
            $collection = new TagCollection();
            return $collection;
        }
    }


    /**
     * Gets all of the children of this tag recursively
     * @return TagCollection
     */
    public function get_children_recursive()
    {
        if(!isset($this->id))
        {
            return null;
        }
        $tagmodels = DataTags::getDataTagsQueryBuilder();
        $tagmodels = $tagmodels->where('parent_tag_id', '=', $this->id)->get();

        if(sizeOf($tagmodels) != 0)
        {
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
        else
        {
            $collection = new TagCollection();
            return $collection;
        }
    }



    /**
     * Checks to see if this tag has children
     * @return bool Returns true if this tag has children
     */
    public function has_children()
    {
        if(!isset($this->id))
        {
            return false;
        }

        if(Tag::where('parent_tag_id', '=', $this->id)->exists())
        {
           return true;
        }
        else
        {
           return false;
        }
    }
    /**
     * Gets the number of layers deep the tag is. This function caches the value after it runs
     * @return int The amount of parents this tag has
     * @throws Exception if the tag's parent_id is not initialized an error is thrown
     */
    public function get_layers_deep()
    {
        if(isset($this->cached_layers_deep))
        {
            return $this->cached_layers_deep;
        }

        if($this->parent_id != null)
        {

            $parent = DataTags::get_by_id($this->parent_id);
            $count = 0;
            while($parent != null)
            {
                $count++;
                $parent = DataTags::get_by_id($parent->parent_id);
            }
            $this->cached_layers_deep = $count;
            return $count;
        }
        else
        {
            throw new Exception('The Tag has to be initialized first. parent_id is null.');
        }
    }



    public function get_layers_deep_to_sheet()
    {
        if(isset($this->cached_layers_deep_to_sheet))
        {
            return $this->cached_layers_deep_to_sheet;
        }
        $count = 0;
        $type_name = "";
        $parent = $this->get_parent();
        if(isset($parent))
        {
            $type_name = $parent->get_type()->getName();
            if($type_name == "sheet")
                $count = 1;
        }



        while($parent != null &&  $type_name != "sheet" )
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
        {
            return $this->updated_at;
        }

        if(isset($this->id))
        {
            /**
             * @var Tag $tag
             */
            $tag = Tag::where('id', '=', $this->id)->first();
            $updated_at = $tag->updated_at;
            $this->updated_at = $updated_at;
            return $updated_at;
        }
        else
        {
            return null;
        }
    }

    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {

        if(isset($this->created_at)) // cached
        {
            return $this->created_at;
        }

        if(isset($this->id))
        {
            /** @var Tag $tag */
            $tag = Tag::where('id', '=', $this->id)->first();
            $created_at = $tag->created_at;
            $this->created_at = $created_at;
            return $created_at;
        }
        else
        {
            return null;
        }
    }



    /**
     * cheks if current tag exists in the database
     * @return bool
     */
    public function exists()
    {
        if(isset($this->id)) {

            return Tag::where("id", "=", $this->id)->exists();
        }
        else
        {
            if(isset($this->name) && isset($this->parent_id))
            {

                return  DataTags::get_by_string($this->name,$this->parent_id ) ? true : false;
            }
            else
            {
                return false;
            }

        }
    }



}