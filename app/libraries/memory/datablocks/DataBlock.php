<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:11 AM
 */

namespace app\libraries\memory\datablocks;


use app\libraries\Data_Blocks\DataBlockAbstract;
use app\libraries\memory\tags\TagCollection;
use app\libraries\memory\types\Type;


/**
 * Class DataBlock
 * @package app\libraries\memory\datablocks
 */
class DataBlock extends DataBlockAbstract
{

    /**
     * DataBlock constructor.
     * @param int $id
     * @param Type $type
     * @param TagCollection $tags
     */
    function __construct($id = null, $type = null, $tags = null, $value = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->tags = $tags;
        $this->value = $value;
    }

    /**
     * @return Type
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Gets the proccessed value of the datablock
     * @return string
     */
    public function getProccessedValue()
    {
        // TODO: Implement getProccessedValue() method.
    }

    /**
     *Creates a datablock in the datablock with the same properties
     * @return bool Returns true if succesful
     */
    public function create()
    {
        // TODO: Implement create() method.
    }

    /**
     * Saves the datablock to the database if it already exists
     * @return bool
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * Deletes the datablock
     * @return bool true if deleted, false if the datablock does not exist
     */
    public function deleteTagsAndDatablock()
    {
        // TODO: Implement deleteTagsAndDatablock() method.
    }

    /**
     * Gets the date at when the object was updated.
     * @return string
     */
    public function updated_at()
    {
       return $this->updated_at;
    }

    /**
     * Gets the date at when the object was created
     * @return string
     */
    public function created_at()
    {
        return $this->created_at;
    }

    /**
     * Deletes The Object
     * @return mixed
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }
}