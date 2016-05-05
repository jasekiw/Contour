<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/5/2016
 * Time: 3:08 PM
 */

namespace app\libraries\datablocks;

use app\libraries\memory\datablocks\DataBlock as MemoryDataBlock;
use app\libraries\types\Type;
use app\libraries\memory\types\Type as MemoryType;

abstract class DataBlockCollectionAbstract
{
    
    const SORT_TYPE_NONE = "NONE";
    const SORT_TYPE_BY_SORT_NUMBER = "sortBySortNumber";
    const SORT_TYPE_BY_ID = "sortById";
    /** @var DataBlock[]   */
    public $blocks = [];
    /** @var DataBlock[]   */
    protected $blocksById = [];
    
    protected $sorted = false;
    
    /**
     * Creates a new Collection Of tags. This does not change anything in the database but allows easy reference to
     * multiple tags
     *
     * @param DataBlock[] | DataBlock | MemoryDataBlock[] |  MemoryDataBlock $inputBlocks
     */
    function __construct($inputBlocks = null)
    {
        if (!isset($inputTags))
            return;
        if (is_array($inputTags)) {
            $this->blocks = $inputTags;
            foreach ($inputTags as $tag)
                $this->blocksById[$tag->get_id()] = $tag;
        } else {
            array_push($this->blocks, $inputTags);
            $this->blocksById[$inputBlocks->get_id()] = $inputTags;
        }
    }
    
    /**
     * Gets the tags as an array. Sort options are available use TagCollection::SORT_TYPE*
     *
     * @param string $sort The sort method to use, defaults to sort by layers
     *
     * @return \app\libraries\tags\DataTag[]
     */
    public function getAsArray($sort = DataBlockCollection::SORT_TYPE_NONE)
    {
        if ($sort == self::SORT_TYPE_NONE)
            return $this->blocks;
        $this->$sort(); // sorts by the method name
        return $this->blocks;
    }
    
    /**
     * Gets all tags that start with the specified string. good for seperating by starting letter
     *
     * @param String $starting
     *
     * @return DataBlock[] | MemoryDataBlock[]
     */
    public function getTagsStartingWith($starting)
    {
        $matches = [];
        foreach ($this->blocks as $tag) {
            $found = strpos(strtoupper($tag->get_name()), strtoupper($starting));
            if ($found !== false && $found == 0)
                array_push($matches, $tag);
        }
        return $matches;
    }
    
    /**
     * @param Type | MemoryType $type
     *
     * @return \app\libraries\tags\DataTag[]
     */
    public function getTagWithTypeAsArray($type)
    {
        $array = [];
        foreach ($this->blocks as $block)
            if ($block->get_type()->getName() == $type->getName())
                array_push($array, $block);
        return $array;
    }
    
    /**
     * @param DataBlock | MemoryDataBlock $block
     *
     * @return bool
     */
    public function add($block)
    {
        if (!isset($block))
            return false;
        array_push($this->blocks, $block);
        $this->blocksById[$block->get_id()] = $block;
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
        if (is_int($input)) {
            $tag = $this->blocksById[$input];
            unset($this->blocksById[$tag->get_id()]);
            foreach ($this->blocks as $index => $tag) {
                if ($tag->get_id() === $input) {
                    unset($this->blocks[$index]);
                    return true;
                }
            }
            return false; // not found
        } else if (strpos(strtoupper(get_class($input)), "TYPE") !== false) {
            /** @var Type $input */
            $found = false;
            foreach ($this->blocks as $index => $tag) {
                $tagtypeName = $tag->get_type()->getName();
                $inputName = $input->getName();
                if ($tagtypeName === $inputName) {
                    $tag = $this->blocks[$index];
                    
                    unset($this->blocksById[$tag->get_id()]);
                    unset($this->blocks[$index]);
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
        return sizeof($this->blocks);
    }
    
    /**
     * Gets a tag by the name or id
     *
     * @param String|Int $input
     *
     * @return DataBlock | null if none found
     */
    public function get($input)
    {
        if (is_int($input))
            return isset($this->blocksById[$input]) ? $this->blocksById[$input] : null;
        return null; // integer or string was not given
    }
    
    /**
     * adds a collection or an aryay of tags to the collection
     *
     * @param DataBlockCollection | DataBlock[] $blocks
     *
     * @return bool
     */
    public function addAll($blocks)
    {
        if (!isset($blocks))
            return false;
        if (is_array($blocks)) {
            $this->blocks = array_merge($this->blocks, $blocks);
            foreach ($blocks as $tag)
                $this->blocksById[$tag->get_id()] = $tag;
        } else {
            $this->blocks = array_merge($this->blocks, $blocks->getAsArray());
            foreach ($blocks->getAsArray() as $tag)
                $this->blocksById[$tag->get_id()] = $tag;
        }
        
        return true;
    }
    
    /**
     * Sorts the internal array of tags by their sort numbers
     */
    private function sortBySortNumber()
    {
        if (empty($this->blocks))
            return;
        $this->sorted = usort($this->blocks, function ($a, $b) {
            /**
             * @var DataBlock $a
             * @var DataBlock $b
             */
            return $a->getSortNumber() - $b->getSortNumber();
        });
    }
    
    private function sortById()
    {
        if (empty($this->blocks))
            return;
        $this->sorted = usort($this->blocks, function ($a, $b) {
            /**
             * @var DataBlock $a
             * @var DataBlock $b
             */
            return $a->get_id() - $b->get_id();
        });
    }
    
}