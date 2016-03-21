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

/**
 * Class TagCollection
 * @package app\libraries\tags\collection
 */
class TagCollection extends TagCollectionAbstract
{

    /**
     * @var DataTag[]
     */
    public $tags = array();
    const SORT_TYPE_BY_LAYERS = "sortByLayers";
    const SORT_TYPE_NONE = "NONE";
    const SORT_TYPE_BY_SORT_NUMBER = "sortBySortNumber";
    const SORT_TYPE_ALPHABETICAL = "sortByAlphabetical";

    /**
     * Creates a new Collection Of tags. This does not change anything in the database but allows easy reference to multiple tags
     * @param DataTag[] | DataTag $inputTags
     * @throws Exception
     */
    function __construct($inputTags = null)
    {
        if(!isset($inputTags))
            return;
        if(is_array($inputTags))
            $this->tags = $inputTags;
        else if(strpos(get_class($inputTags), "DataTag") !== false)
            array_push($this->tags,$inputTags);
    }


    /**
     * Gets the tags as an array. Sort options are available use TagCollection::SORT_TYPE*
     * @param string $sort The sort method to use, defaults to sort by layers
     * @return \app\libraries\tags\DataTag[]
     */
    public function getAsArray($sort = TagCollection::SORT_TYPE_BY_LAYERS)
    {
        if($sort == self::SORT_TYPE_NONE)
            return $this->tags;
        $this->$sort(); // sorts by the method name
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
            $found = strpos(strtoupper($tag->get_name()), strtoupper($starting) );
            if($found !== false && $found == 0)
                array_push($matches, $tag);
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
            if($tag->get_type()->getName() == Types::get_type_column()->getName())
                array_push($array, $tag);
        return $array;
    }

    /**
     * @return DataTag[]
     */
    public function getRowsAsArray()
    {
        $array = array();
        foreach($this->tags as $tag)
            if($tag->get_type()->getName() == Types::get_type_row()->getName())
                array_push($array, $tag);
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
            if($tag->get_type()->getName() == $type->getName())
                array_push($array, $tag);
        return $array;
    }



    /**
     * @param DataTag $tag
     * @return bool
     */
    public function add($tag)
    {
        if(!isset($tag))
            return false;
        array_push($this->tags, $tag);
        return true;
    }

    /**
     * Removes the specified tag from the array. An integer ID can be given or a String name can be given
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
        else if(strpos(strtoupper(get_class($input)),"TYPE") !== false )
        {
            /** @var Type $input */
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

    /**
     * Gets the size of the array
     * @return int The size of the array
     */
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
                if(strtoupper($tag->get_name()) === strtoupper($input) )
                    return $tag;
            return null; // not found
        }
        else if (is_int($input))
        {
            foreach($this->tags as $tag)
                if($tag->get_id() === $input)
                    return $tag;
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
            return false;
        if(is_array($tags))
            $this->tags = array_merge($this->tags, $tags);
        else
            $this->tags = array_merge($this->tags,  $tags->getAsArray());
        return true;
    }

    /**
     * Sorts the internal array of tags by their sort numbers
     */
    private function sortBySortNumber()
    {
        if(empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function($a, $b) {
            /**
             * @var DataTag $a
             * @var DataTag $b
             */
            return $a->get_sort_number() - $b->get_sort_number();
        });
    }

    private function sortByAlphabetical()
    {
        if(empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function($a, $b) {
            /**
             * @var DataTag $a
             * @var DataTag $b
             */
            return strcmp($a->get_name(), $b->get_name());
        });
    }

    /**
     * Sorts the internal array of tags by how deep they are. Low to High
     */
    private function sortByLayers()
    {
        if(empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function($a, $b) {
            /**
             * @var DataTag $a
             * @var DataTag $b
             */
            return $a->get_layers_deep() - $b->get_layers_deep();
        });
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