<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/5/2015
 * Time: 10:32 AM
 */

namespace app\libraries\types;

use App\Models\TypeModel;

class Type extends TypeAbstract
{
    
    private $category_id = null;
    
    /**
     * @param String $name
     */
    function __construct(string $name = null)
    {
        $this->name = $name;
    }
    
    /**
     * Sets the ID of the Type Object
     *
     * @param int $id
     */
    public function set_id(int $id)
    {
        $this->id = $id;
    }
    
    /**
     * Sets the Instance by the row ID
     *
     * @param int $id
     */
    public function setByID(int $id)
    {
        $typemodel = TypeModel::where('id', '=', $id)->first();
        if (!isset($typemodel))
            return;
        $this->id = $typemodel->id;
        $this->name = $typemodel->name;
        $this->setCategory(TypeCategory::GetByID($typemodel->type_category_id));
    }
    
    /**
     * Sets the category and id by id
     *
     * @param TypeCategory $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    
    /**
     * @param String $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function save()
    {
        if (isset($this->id)) {
            $type = TypeModel::where('id', '=', $this->id)->first();
            if (isset($type)) {
                $type->name = $this->getName();
                $type->type_category_id = $this->getCategory()->get_id();
                $type->save();
            } else
                $this->createNew();
        } else
            $this->createNew();
    }
    
    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return TypeCategory
     */
    public function getCategory()
    {
        if (isset($this->category))
            return $this->category;
        $this->category = TypeCategory::GetByID($this->getCategoryId());
        return $this->category;
    }
    
    public function getCategoryId()
    {
        if (!isset($this->id))
            return null;
        if (isset($this->category_id))
            return $this->category_id;
        $this->category_id = TypeModel::where('id', '=', $this->id)->first()->type_category_id;
        return $this->category_id;
    }
    
    public function setCategoryId($id)
    {
        $this->category_id = $id;
    }
    
    /**
     * If the tag does not exist in the database then create a new one and set the id
     */
    private function createNew()
    {
        $type = new TypeModel();
        $type->name = $this->getName();
        $type->type_category_id = $this->getCategory()->get_id();
        $type->save();
        $this->id = $type->id;
    }
    
    /**
     * Gets the date at when the object was updated.
     * @return String
     */
    public function updated_at()
    {
        if (isset($this->updated_at)) // cached
            return $this->updated_at;
        if (!isset($this->id))
            return null;
        $type = TypeModel::where('id', '=', $this->id)->first();
        $updated_at = $type->updated_at;
        $this->updated_at = $updated_at;
        return $updated_at;
    }
    
    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if (isset($this->created_at)) // cached
            return $this->created_at;
        if (!isset($this->id))
            return null;
        $type = TypeModel::where('id', '=', $this->id)->first();
        $created_at = $type->created_at;
        $this->created_at = $created_at;
        return $created_at;
    }
    
    public function delete()
    {
        TypeModel::where('id', '=', $this->id)->delete();
        $this->id = null;
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
        $std->id = $this->get_id();
        return $std;
    }
    
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
}