<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 9:23 AM
 */

namespace app\libraries\types;


use app\libraries\database\DatabaseObject;

/**
 * Class TypeCategoryAbstract
 * @package app\libraries\Types
 */
abstract class TypeCategoryAbstract extends DatabaseObject
{
    protected $name = null;


    /**
     * @param int $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the ID
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Gets the name
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *  Saves the TypeCategory to the Database
     * @return bool sucessfull
     */
    public abstract function save();

}