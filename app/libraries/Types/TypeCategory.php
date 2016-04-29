<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/5/2015
 * Time: 10:39 AM
 */

namespace app\libraries\types;

use app\libraries\database\DatabaseObject;
use App\Models\Type_category;

/**
 * Class TypeCategory
 * @package app\libraries\types
 */
class TypeCategory extends TypeCategoryAbstract
{


    private static $cachedCategories = [];

    /**
     * @param int $id
     * @return TypeCategory
     */
    public static function GetByID($id)
    {
        if(isset(self::$cachedCategories[$id]))
            return self::$cachedCategories[$id];
        $category = Type_category::where('id', '=', $id)->first();
        if(!isset($category))
            return null;
        $typecategory = new TypeCategory();
        $typecategory->name = $category->name;
        $typecategory->id = $category->id;
        self::$cachedCategories[$id] = $typecategory;
        return $typecategory;
    }

    /**
     * @param String $name
     * @return TypeCategory
     */
    public static function getByName($name)
    {
        $category = Type_category::where('name', '=', $name)->first();
        if(isset($category))
        {
            $typecategory = new TypeCategory();
            $typecategory->name = $category->name;
            $typecategory->id = $category->id;
            return $typecategory;
        }
        return null;
    }

    /**
     * Gets the TypeCategory with the name of tag
     * @return TypeCategory
     */
    public static function getTagCategory()
    {
        $category = Type_category::where('name', '=', "tag")->first();
        if(!isset($category))
        {
            $category = new Type_category();
            $category->name = "tag";
            $category->save();
        }
        $typecategory = new TypeCategory();
        $typecategory->name = $category->name;
        $typecategory->id = $category->id;
        return $typecategory;
    }

    /**
     * Gets the TypeCategory with the name of DataBlock
     * @return TypeCategory
     */
    public static function getDataBlockCategory()
    {

        $category = Type_category::where('name', '=', "datablock")->first();
        if(!isset($category))
        {
            $category = new Type_category();
            $category->name = "datablock";
            $category->save();
        }
        $typecategory = new TypeCategory();
        $typecategory->name = $category->name;
        $typecategory->id = $category->id;
        return $typecategory;
    }

    /**
     * Gets the typecategory by a Type_category row
     * @param $row
     * @return TypeCategory
     */
    public static function getByQueryRow($row)
    {
        $category = new TypeCategory();
        $category->set_id($row->type_category_id);
        $category->setName($row->type_category_name);
        return $category;
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
    public function save()
    {
        if($this->name == null)
            return false;
        if(isset($this->id))
        {
            $typemodel = Type_category::where('id', '=', $this->id)->first();
            if(isset($typemodel))
            {
                $typemodel->id = $this->id;
                $typemodel->name = $this->name;
                $typemodel->save();
                return true;
            }
            return false;
        }

        $typemodel = new Type_category();
        $typemodel->id = $this->id;
        $typemodel->name = $this->name;
        $typemodel->save();
        return true;
    }

    /**
     * Deletes the TypeCategory
     * @throws \Exception
     */
    public function delete()
    {
        Type_category::where('id', '=', $this->id)->delete();
        $this->id = null;
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
     * Gets the date at when the object was updated.
     * @return String
     */
    public function updated_at()
    {
        if(isset($this->updated_at)) // cached
            return $this->updated_at;
        if(isset($this->id))
        {
            $typeCategory = Type_category::where('id', '=', $this->id)->first();
            /**
             * \Tag $tag
             */
            $updated_at = $typeCategory->updated_at;
            $this->updated_at = $updated_at;
            return $updated_at;
        }
        return null;
    }

    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if(isset($this->created_at)) // cached
            return $this->created_at;
        if(isset($this->id))
        {
            $typeCategory = Type_category::where('id', '=', $this->id)->first();
            $created_at = $typeCategory->created_at;
            $this->created_at = $created_at;
            return $created_at;
        }
        return null;
    }
}