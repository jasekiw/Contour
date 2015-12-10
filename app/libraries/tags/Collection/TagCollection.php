<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/30/2015
 * Time: 11:19 AM
 */

namespace app\libraries\tags\collection;

use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Type;
use app\libraries\types\Types;
use \Exception;

class TagCollection
{

//    public $ClassTag = null;
//    public $CategoryTag = null;
//    public $InstanceTag = null;
//    public $DescripterTag = null;
    /**
     * @var DataTag[]
     */
    public $tags = array();
    private $sorted = false;

    /**
     * Creates a new Collection Of tags. This does not change anything in the database but allows easy reference to multiple tags
     * @param DataTag[] | DataTag $inputTags
     * @throws Exception
     */
    function __construct($inputTags = null)
    {
        if(!isset($inputTags))
        {
            return;
        }
        if(is_array($inputTags))
        {
            if(get_class(array_values($inputTags)[0]) == 'app\libraries\tags\DataTag')
            {
                $this->tags = $inputTags;
            }
            else{
                throw new Exception('Contructor tags have to be of type DataTag or an array of that type');
            }

        }
        else if(get_class($inputTags) == 'app\libraries\tags\DataTag')
        {
            array_push($this->tags,$inputTags);
        }
//        $this->ClassTag = $this->isOfType($class) ? new TagSubClass("Class", $class) : new TagSubClass("Class");
//        $this->CategoryTag = $this->isOfType($category) ? new TagSubClass("Category", $category) : new TagSubClass("Category");
//        $this->InstanceTag = $this->isOfType($instance) ? new TagSubClass("Instance", $instance) : new TagSubClass("Instance");
//        $this->descripterTag = $this->isOfType($descriptor) ? new TagSubClass("Descriptor", $descriptor) : new TagSubClass("Descriptor");

    }

    /**
     * @return array
     */
//   public function getAsArrayAssoc()
//   {
//       $theArray = array();
//       if($this->ClassTag->is_set())
//       {
//           $theArray["class"] = $this->ClassTag->get();
//       }
//       if($this->CategoryTag->is_set())
//       {
//           $theArray["category"] = $this->CategoryTag->get();
//       }
//       if($this->InstanceTag->is_set())
//       {
//           $theArray["isntance"] = $this->InstanceTag->get();
//       }
//       if($this->descripterTag->is_set())
//       {
//           $theArray["descriptor"] = $this->descripterTag->get();
//       }
//       return $theArray;
//
//   }

    /**
     * @return DataTag[]
     */
    public function getAsArray()
    {
        if(!$this->sorted && sizeOf($this->tags) > 0)
        {
            $this->sortByLayers();
        }

        return $this->tags;

    }

    /**
     * Gets all tags that start with the specified string. good for seperating by starting letter
     * @param String $starting
     * @return DataTag[]
     */
    public function getTagsStartingWith($starting)
    {
        $matches = array();
        foreach($this->tags as $tag)
        {
            $found = strpos($tag->get_name(),$starting );

            if($found !== false && $found == 0)
            {
                array_push($matches, $tag);
            }
        }
        return $matches;
    }

    /**
     * @return DataTag[]
     */
    public function getColumnsAsArray()
    {
        $array = array();
        foreach($this->tags as $tag)
        {
            if($tag->get_type()->getName() == Types::get_type_column()->getName())
            {
                array_push($array, $tag);
            }
        }
        return $array;
    }

    /**
     * @return DataTag[]
     */
    public function getRowsAsArray()
    {
        $array = array();
        foreach($this->tags as $tag)
        {
            if($tag->get_type()->getName() == Types::get_type_row()->getName())
            {
                array_push($array, $tag);
            }
        }
        return $array;
    }


    /**
     * @param Type $type
     * @return \app\libraries\tags\DataTag[]
     */
    public function getTagWithTypeAsArray($type)
    {
        $array = array();
        foreach($this->tags as $tag)
        {
            if($tag->get_type()->getName() == $type->getName())
            {
                array_push($array, $tag);
            }
        }
        return $array;
    }



    /**
     * @param DataTag $tag
     * @return bool
     */
    public function add(DataTag $tag)
    {
        if(!isset($tag))
        {
            return false;
        }
        array_push($this->tags, $tag);
        return true;
    }

    /**Removes the specified tag from the array. An integer ID can be given or a String name can be given
     * @param String|Int|Type $input
     * @return bool true if successful, false if not found
     */
    public function remove($input)
    {
        if(is_string($input))
        {
            foreach($this->tags as $index => $tag)
            {
                if(strtoupper($tag->get_name()) === strtoupper($input) )
                {
                    unset($this->tags[$index]);
                    return true;
                }
            }
            return false; // not found
        }
        else if (is_int($input))
        {
            foreach($this->tags as $index => $tag)
            {
                if($tag->get_id() === $input)
                {
                    unset($this->tags[$index]);
                    return true;
                }
            }
            return false; // not found
        }
        else if(strpos(strtoupper(get_class($input)),"TYPE") >= 0 )
        {
            /**
             * @var Type $input
             */
            $found = false;
            foreach($this->tags as $index => $tag)
            {
                $tagtypeName = $tag->get_type()->getName();
                $inputName = $input->getName();
                if($tagtypeName === $inputName)
                {
                    unset($this->tags[$index]);
                    $found = true;
                }
            }
            return $found; // not found

        }
        return false; // integer or string was not given
    }

    public function getSize()
    {
        return sizeOf($this->tags);
    }

    /**
     * Gets a tag by the name or id
     * @param String|Int $input
     * @return DataTag Null if none found
     */
    public function get($input)
    {
        if(is_string($input))
        {
            foreach($this->tags as $tag)
            {
                if(strtoupper($tag->get_name()) === strtoupper($input) )
                {
                    return $tag;

                }
            }
            return null; // not found
        }
        else if (is_int($input))
        {
            foreach($this->tags as $tag)
            {
                if($tag->get_id() === $input)
                {
                    return $tag;

                }
            }
            return null; // not found
        }
        return null; // integer or string was not given
    }

    /**
     * adds a collection or an aryay of tags to the collection
     * @param TagCollection|DataTag[] $tags
     * @return bool
     */
    public function addAll( $tags)
    {
        if(!isset($tags))
        {
            return false;
        }

        if(is_array($tags))
        {
            if(sizeOf($tags) > 0)
            {
                foreach($tags as $tag)
                {
                    array_push($this->tags, $tag);
                }

            }

        }
        else
        {
            $array = $tags->getAsArray();
            if(sizeOf($array) > 0)
            {
                foreach($array as $tag)
                {
                    array_push($this->tags, $tag);
                }

            }

        }

        return true;
    }


    /**
     * Sorts the internal array of tags by how deep they are. Low to High
     */
    private function sortByLayers()
    {
        if(sizeOf($this->tags) == 0)
        {
            return;
        }
        $oldArray = $this->tags;
        $satisfied = false;
        $newArray = array();
        while(!$satisfied)
        {
            $count = 0;
            $lowest = null;
            $lowestIndex = -1;

            foreach($oldArray as $index => $tag)
            {
                /** @var DataTag $tag */
                if($count == 0)
                {
                    $lowest = $tag->get_layers_deep();
                    $lowestIndex = $index;
                }
                else
                {
                    $current = $tag->get_layers_deep();
                    if( $current < $lowest)
                    {
                        $lowest = $current;
                        $lowestIndex = $index;
                    }
                }
                $count++;
            }
            array_push($newArray, $oldArray[$lowestIndex]);
            unset($oldArray[$lowestIndex]);
            if(sizeOf($oldArray) == 0)
            {
                $satisfied = true;
            }
        }
        $this->tags = $newArray;
        $this->sorted = true;
    }

    /**
     * @param $input
     * @return TagCollection
     */
    public static function getTagsFromCommaDelimited($input)
    {
        $tags = explode("][", $input);
        $collection = new TagCollection();
        foreach($tags as $tag)
        {
            $tag = str_replace("]", "",$tag);
            $tag = str_replace("[", "",$tag);
            $collection->add(DataTags::get_by_id($tag));
        }

        return $collection;
    }


}