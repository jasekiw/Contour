<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 10:59 AM
 */

namespace app\libraries\tags\collection;

use app\libraries\memory\types\Type as MemoryType;
use app\libraries\tags\DataTagAbstract;
use app\libraries\types\Type;
use app\libraries\tags\DataTag;
use app\libraries\memory\tags\DataTag as MemoryTag;
use Exception;

/**
 * Class TagCollectionAbstract
 * @package app\libraries\tags\Collection
 */
abstract class TagCollectionAbstract
{
    
    const SORT_TYPE_BY_LAYERS = "sortByLayers";
    const SORT_TYPE_NONE = "NONE";
    const SORT_TYPE_BY_SORT_NUMBER = "sortBySortNumber";
    const SORT_TYPE_ALPHABETICAL = "sortByAlphabetical";
    /** @var DataTag[]   */
    public $tags = [];
    /** @var DataTag[]   */
    protected $tagsById = [];
    /** @var DataTag[]   */
    protected $tagsByName = [];
    protected $sorted = false;
    
    /**
     * Creates a new Collection Of tags. This does not change anything in the database but allows easy reference to
     * multiple tags
     *
     * @param DataTag[] | MemoryTag[] | DataTag | MemoryTag $inputTags
     */
    function __construct($inputTags = null)
    {
        if (!isset($inputTags))
            return;
        if (is_array($inputTags)) {
            $this->tags = $inputTags;
            foreach ($inputTags as $tag) {
                $this->tagsById[$tag->get_id()] = $tag;
                $this->tagsByName[$tag->get_name()] = $tag;
            }
        } else {
            array_push($this->tags, $inputTags);
            $this->tagsById[$inputTags->get_id()] = $inputTags;
            $this->tagsByName[$inputTags->get_name()] = $inputTags;
        }
    }
    
    /**
     * Gets the tags as an array. Sort options are available use TagCollection::SORT_TYPE*
     *
     * @param string $sort The sort method to use, defaults to sort by layers
     *
     * @return \app\libraries\tags\DataTag[]
     */
    public function getAsArray($sort = TagCollection::SORT_TYPE_BY_LAYERS)
    {
        if ($sort == self::SORT_TYPE_NONE)
            return $this->tags;
        $this->$sort(); // sorts by the method name
        return $this->tags;
    }
    
    /**
     * Gets all tags that start with the specified string. good for seperating by starting letter
     *
     * @param String $starting
     *
     * @return DataTag[] | MemoryTag[]
     */
    public function getTagsStartingWith($starting)
    {
        $matches = [];
        foreach ($this->tags as $tag) {
            $found = strpos(strtoupper($tag->get_name()), strtoupper($starting));
            if ($found !== false && $found == 0)
                array_push($matches, $tag);
        }
        return $matches;
    }
    
    /**
     * @return DataTag[]
     */
    public abstract function getColumnsAsArray();
    
    /**
     * @return DataTag[] | MemoryTag[]
     */
    public abstract function getRowsAsArray();
    
    /**
     * @param Type | MemoryType $type
     *
     * @return \app\libraries\tags\DataTag[]
     */
    public function getTagWithTypeAsArray($type)
    {
        $array = [];
        foreach ($this->tags as $tag)
            if ($tag->get_type()->getName() == $type->getName())
                array_push($array, $tag);
        return $array;
    }
    
    /**
     * @param DataTag | MemoryTag $tag
     *
     * @return bool
     */
    public function add($tag)
    {
        if (!isset($tag))
            return false;
        array_push($this->tags, $tag);
        $this->tagsById[$tag->get_id()] = $tag;
        $this->tagsByName[$tag->get_name()] = $tag;
        return true;
    }
    
    /**
     * Removes the specified tag from the array. An integer ID can be given or a String name can be given
     *
     * @param String|Int|Type $input
     *
     * @return bool true if successful, false if not found
     */
    public function remove($input)
    {
        if (is_string($input)) {
            $tag = $this->tagsByName[$input];
            unset($this->tagsByName[$input]);
            unset($this->tagsById[$tag->get_id()]);
            foreach ($this->tags as $index => $tag) {
                if (strtoupper($tag->get_name()) === strtoupper($input)) {
                    unset($this->tags[$index]);
                    return true;
                }
            }
            return false; // not found
        } else if (is_int($input)) {
            $tag = $this->tagsById[$input];
            unset($this->tagsByName[$tag->get_name()]);
            unset($this->tagsById[$tag->get_id()]);
            foreach ($this->tags as $index => $tag) {
                if ($tag->get_id() === $input) {
                    unset($this->tags[$index]);
                    return true;
                }
            }
            return false; // not found
        } else if (strpos(strtoupper(get_class($input)), "TYPE") !== false) {
            /** @var Type $input */
            $found = false;
            foreach ($this->tags as $index => $tag) {
                $tagtypeName = $tag->get_type()->getName();
                $inputName = $input->getName();
                if ($tagtypeName === $inputName) {
                    $tag = $this->tags[$index];
                    unset($this->tagsByName[$tag->get_name()]);
                    unset($this->tagsById[$tag->get_id()]);
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
        return sizeof($this->tags);
    }
    
    /**
     * Gets a tag by the name or id
     *
     * @param String|Int $input
     *
     * @return DataTag Null if none found
     */
    public function get($input)
    {
        if (is_string($input))
            return isset($this->tagsByName[$input]) ? $this->tagsByName[$input] : null;
        else if (is_int($input))
            return isset($this->tagsById[$input]) ? $this->tagsById[$input] : null;
        return null; // integer or string was not given
    }
    
    /**
     * adds a collection or an aryay of tags to the collection
     *
     * @param TagCollection|DataTag[] $tags
     *
     * @return bool
     */
    public function addAll($tags)
    {
        if (!isset($tags))
            return false;
        if (is_array($tags)) {
            $this->tags = array_merge($this->tags, $tags);
            foreach ($tags as $tag) {
                $this->tagsById[$tag->get_id()] = $tag;
                $this->tagsByName[$tag->get_name()] = $tag;
            }
        } else {
            $this->tags = array_merge($this->tags, $tags->getAsArray());
            foreach ($tags->getAsArray() as $tag) {
                $this->tagsById[$tag->get_id()] = $tag;
                $this->tagsByName[$tag->get_name()] = $tag;
            }
        }
        
        return true;
    }
    
    /**
     * Sorts the internal array of tags by their sort numbers
     */
    private function sortBySortNumber()
    {
        if (empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function ($a, $b) {
            /**
             * @var DataTag $a
             * @var DataTag $b
             */
            return $a->get_sort_number() - $b->get_sort_number();
        });
    }
    
    private function sortByAlphabetical()
    {
        if (empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function ($a, $b) {
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
        if (empty($this->tags))
            return;
        $this->sorted = usort($this->tags, function ($a, $b) {
            /**
             * @var DataTag $a
             * @var DataTag $b
             */
            return $a->get_layers_deep() - $b->get_layers_deep();
        });
    }
    
}