<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/5/2016
 * Time: 3:07 PM
 */

namespace app\libraries\datablocks;

use app\libraries\memory\datablocks\DataBlock as MemoryDataBlock;
use app\libraries\types\Type;
use app\libraries\types\Types;
use Exception;

/**
 * Class DataBlockCollection
 * @package app\libraries\datablocks
 */
class DataBlockCollection extends DataBlockCollectionAbstract
{
    
    /**
     * Creates a new Collection Of tags. This does not change anything in the database but allows easy reference to
     * multiple tags
     *
     * @param DataBlock[] | DataBlock $inputBlocks
     *
     * @throws Exception
     */
    function __construct($inputBlocks = null)
    {
        parent::__construct();
        if (!isset($inputBlocks))
            return;
        if (is_array($inputBlocks))
            $this->blocks = $inputBlocks;
        else if (strpos(get_class($inputBlocks), "DataBlock") !== false)
            array_push($this->blocks, $inputBlocks);
    }
    
    /**
     * Gets all tags that start with the specified string. good for seperating by starting letter
     *
     * @param String $starting
     *
     * @return DataBlock[]
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
     * @return DataBlock[]
     */
    public function getColumnsAsArray()
    {
        $array = [];
        foreach ($this->blocks as $tag)
            if ($tag->get_type()->getName() == Types::get_type_column()->getName())
                array_push($array, $tag);
        return $array;
    }
    
    /**
     * @return DataBlock[]
     */
    public function getRowsAsArray()
    {
        $array = [];
        foreach ($this->blocks as $tag)
            if ($tag->get_type()->getName() == Types::get_type_row()->getName())
                array_push($array, $tag);
        return $array;
    }
    
    /**
     * @param Type $type
     *
     * @return DataBlock[]
     */
    public function getTagWithTypeAsArray($type)
    {
        $array = [];
        foreach ($this->blocks as $tag)
            if ($tag->get_type()->getName() == $type->getName())
                array_push($array, $tag);
        return $array;
    }
    
    /**
     * @param Type[] $types
     *
     * @return DataBlock[]
     */
    public function getByTypes($types)
    {
        $nameArray = [];
        foreach ($types as $type)
            array_push($nameArray, $type->getName());
        $tags = [];
        foreach ($this->blocks as $tag)
            if (in_array($tag->get_type()->getName(), $nameArray))
                array_push($tags, $tag);
        return $tags;
    }
    
    /**
     * @param DataBlock $block
     *
     * @return bool
     */
    public function add($block)
    {
        if (!isset($block))
            return false;
        array_push($this->blocks, $block);
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
            foreach ($this->blocks as $index => $tag) {
                if (strtoupper($tag->get_name()) === strtoupper($input)) {
                    unset($this->blocks[$index]);
                    return true;
                }
            }
            return false; // not found
        } else if (is_int($input)) {
            foreach ($this->blocks as $index => $tag) {
                if ($tag->get_id() === $input) {
                    unset($this->blocks[$index]);
                    return true;
                }
            }
            return false; // not found
        } else if (is_array($input))
            return $this->removeByTypes($input);
        else if (strpos(strtoupper(get_class($input)), "TYPE") !== false)
            return $this->removeByTypes([$input]);
        return false; // integer or string was not given
    }
    
    /**
     * @param Type[] $types
     *
     * @return bool
     */
    public function removeByTypes($types)
    {
        $nameArray = [];
        foreach ($types as $type)
            array_push($nameArray, $type->getName());
        $found = false;
        foreach ($this->blocks as $index => $block) {
            $tagtypeName = $block->get_type()->getName();
            
            if (in_array($tagtypeName, $nameArray)) {
                unset($this->blocks[$index]);
                $found = true;
            }
        }
        return $found; // not found
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
     * adds a collection or an aryay of tags to the collection
     *
     * @param DataBlockCollection|DataBlock[] $blocks
     *
     * @return bool
     */
    public function addAll($blocks)
    {
        if (!isset($blocks))
            return false;
        if (is_array($blocks))
            $this->blocks = array_merge($this->blocks, $blocks);
        else
            $this->blocks = array_merge($this->blocks, $blocks->getAsArray());
        return true;
    }
    
    /**
     * Gets the tags as an array. Sort options are available use TagCollection::SORT_TYPE*
     *
     * @param string $sort The sort method to use, defaults to sort by layers
     *
     * @return DataBlock[]
     */
    public function getAsArray($sort = DataBlockCollection::SORT_TYPE_NONE)
    {
        if ($sort == self::SORT_TYPE_NONE)
            return $this->blocks;
        $this->$sort(); // sorts by the method name
        return $this->blocks;
    }
    public function getAssociativeArrayOfSortNumber()
    {
        $sortedBlocks = [];
        foreach($this->blocks as $block)
            $sortedBlocks[$block->getSortNumber()] = $block;
        return $sortedBlocks;
    }
    
}