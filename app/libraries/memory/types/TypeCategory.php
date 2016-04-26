<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 9:22 AM
 */

namespace app\libraries\memory\types;


use app\libraries\types\TypeCategoryAbstract;

/**
 * Class TypeCategory
 * @package app\libraries\memory\types
 */
class TypeCategory extends TypeCategoryAbstract
{

    /**
     * TypeCategory constructor.
     * @param null $id
     * @param null $name
     * @param null $updated_at
     * @param null $created_at
     */
    function __construct($id = null, $name = null, $updated_at = null, $created_at = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
    }

    /**
     * Deletes The Object
     * @return mixed
     */
    public function delete()
    {

    }

    /**
     *  Saves the TypeCategory to the Database
     * @return bool sucessfull
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
        $std->id = $this->get_id();
        $std->name = $this->getName();
        $std->updated_at = $this->updated_at();
        $std->created_at = $this->created_at();
        return $std;
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