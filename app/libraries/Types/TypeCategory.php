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

class TypeCategory extends DatabaseObject
{

    private $name = null;

    public function __construct() {

    }

    /**
     * @param int $id
     * @return TypeCategory
     */
    public static function GetByID($id)
    {
        $category = Type_category::where('id', '=', $id)->first();
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

    public static function getTagCategory()
    {

        $category = Type_category::where('name', '=', "tag")->first();
        if(!isset($category))
        {
            $category = new Type_category();
            $category->name = "tag";
            $category->save();

        }
        if(isset($category))
        {
            $typecategory = new TypeCategory();
            $typecategory->name = $category->name;
            $typecategory->id = $category->id;
            return $typecategory;
        }
        return null;
    }

    public static function getDataBlockCategory()
    {

        $category = Type_category::where('name', '=', "datablock")->first();
        if(!isset($category))
        {
            $category = new Type_category();
            $category->name = "datablock";
            $category->save();

        }
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
     * @param int $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     *  Saves the TypeCategory to the Database
     * @return bool sucessfull
     */
    public function save()
    {
        if(isset($this->id))
        {
            if(isset($this->name))
            {
                $typemodel = Type_category::where('id', '=', $this->name)->first();
                if(isset($typemodel))
                {
                    $typemodel->id = $this->id;
                    $typemodel->name = $this->name;
                    $typemodel->save();
                    return true;
                }
            }
        }
        else
        {
            if(isset($this->name))
            {
                $typemodel = new Type_category();
                $typemodel->id = $this->id;
                $typemodel->name = $this->name;
                $typemodel->save();
                return true;
            }
        }
        return false;
    }


    /**
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
     * Gets the date at when the object was updated.
     * @return String
     */
    public function updated_at()
    {
        if(isset($this->updated_at)) // cached
        {
            return $this->updated_at;
        }

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
        else
        {
            return null;
        }
    }

    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if(isset($this->created_at)) // cached
        {
            return $this->created_at;
        }

        if(isset($this->id))
        {
            $typeCategory = Type_category::where('id', '=', $this->id)->first();
            /**
             * \Tag $tag
             */
            $created_at = $typeCategory->created_at;
            $this->created_at = $created_at;
            return $created_at;
        }
        else
        {
            return null;
        }
    }
}