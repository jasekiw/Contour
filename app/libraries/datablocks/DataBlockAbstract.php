<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 10:48 AM
 */

namespace app\libraries\Data_Blocks;


use app\libraries\database\DatabaseObject;
use app\libraries\memory\tags\TagCollection;
use app\libraries\memory\types\Type as MemoryType;
use app\libraries\types\Type;

/**
 * Class DataBlockAbstract
 * @package app\libraries\Data_Blocks
 */
abstract class DataBlockAbstract extends DatabaseObject
{
    /**
     * @var TagCollection
     * @access private
     */
    protected $tags = null;
    /**
     * @var string
     * @access private
     */
    protected $value = "";
    /**
     * @var Type | MemoryType
     * @access private
     */
    protected $type = null;

    protected $sort_number = 0;

    /**
     * @return Type | MemoryType
     */
    public abstract function get_type();

    /**
     * Sets the type of the datablock
     * @param Type $type
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Gets the value of the DataBlock
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the datablock. It can only be a string
     * @param string $value
     */
    public function set_value($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getSortNumber()
    {
        return $this->sort_number;
    }

    /**
     * @param int $number
     */
    public function setSortNumber($number)
    {
        if(isset($number))
            $this->sort_number = $number;
    }

    /**
     * Gets the proccessed value of the datablock
     * @return string
     */
    public abstract function getProccessedValue();

    /**
     *Creates a datablock in the datablock with the same properties
     * @return bool Returns true if succesful
     */
    public abstract function create();

    /**
     * Saves the datablock to the database if it already exists
     * @return bool
     */
    public abstract function save();

    /**
     * Gets the collection of tags that are used to identify the datablock
     * @return TagCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tags by Tag Collection
     * @param TagCollection $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Deletes the datablock
     * @return bool true if deleted, false if the datablock does not exist
     */
    public abstract function deleteTagsAndDatablock();


}