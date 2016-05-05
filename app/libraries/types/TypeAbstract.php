<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 9:50 AM
 */

namespace app\libraries\types;

use app\libraries\database\DatabaseObject;

/**
 * Class TypeAbstract
 * @package app\libraries\Types
 */
abstract class TypeAbstract extends DatabaseObject
{
    
    /** @var string $name   */
    protected $name = null;
    
    /** @var TypeCategory $category   */
    protected $category = null;
    
    /**
     * Gets the Category Id
     * @return mixed
     */
    public abstract function getCategoryId();
    
    /**
     * Saves the Type
     * @return mixed
     */
    public abstract function save();
    
    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param String $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    
    /**
     * @return TypeCategory
     */
    public abstract function getCategory();
    
    /**
     * Sets the category and id by id
     *
     * @param TypeCategory $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    
}