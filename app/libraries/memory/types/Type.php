<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:22 AM
 */

namespace app\libraries\memory\types;


use app\libraries\types\TypeAbstract;
use app\libraries\memory\types\TypeCategory;

/**
 * Class Type
 * @package app\libraries\memory\types
 */
class Type extends TypeAbstract
{

    /**
     * Type constructor.
     * @param string $name
     * @param TypeCategory $typeCategory
     * @param null $updated_at
     * @param null $created_at
     */
    function __construct($id = null, $name = null, $typeCategory = null, $updated_at = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $typeCategory;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
    }

    /**
     * Deletes The Object
     * @return mixed
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Saves the Type
     * @return mixed
     */
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    public function toStdClass()
    {
        $std = new \stdClass();
        $std->name = $this->name;
        $std->category_id = $this->getCategoryId();
        $std->category = $this->getCategory();
        $std->updated_at = $this->updated_at();
        $std->created_at = $this->created_at();
        $std->id = $this->get_id();
        return $std;
    }

    /**
     * Gets the Category Id
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category->get_id();
    }

    /**
     * @return TypeCategory
     */
    public function getCategory()
    {
        return $this->category;
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
}