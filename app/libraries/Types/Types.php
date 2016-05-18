<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/5/2015
 * Time: 11:23 AM
 */

namespace app\libraries\types;

use App\Models\Type_category;
use App\Models\TypeModel;

/**
 * @property  $category_name
 */
class Types
{
    
    private static $cachedCategoryExists = null;
    private static $cached = [];
    private static $cachedTypes = [];
    
    /**
     * @param String|integer $nameorID check if type exists by id or by name
     *
     * @return bool
     */
    public static function exists($nameorID)
    {
        if (strtoupper(gettype($nameorID)) == "INTEGER")
            return TypeModel::where("id", "=", $nameorID)->exists();
        else
            return TypeModel::where("name", "=", $nameorID)->exists();
    }
    
    /**
     * @return Type
     */
    public static function get_type_sheet()
    {
        return self::getTypeWithName("sheet", "tag");
    }
    
    /**
     * @param        $name
     * @param String $whatType
     *
     * @return Type
     */
    private static function getTypeWithName($name, $whatType)
    {
        if (isset(self::$cached[$name]))
            return self::$cached[$name];
        self::createTypeCategoryTag();
        /** @var \Illuminate\Database\Query\Builder $type */
        $type = self::getQueryObject();
        $row = $type->where("types.name", "=", $name)->where('category.name', '=', $whatType)->first();
        if (!isset($row)) {
            /** @var TypeModel $newtype */
            $category_row = Type_category::where("name", "=", $whatType)->first();
            $newtype = new TypeModel();
            $newtype->name = $name;
            $newtype->type_category_id = $category_row->id;
            $newtype->save();
            $tagType = new Type();
            $tagType->set_id($newtype->id);
            $tagType->setName($name);
            $category = new TypeCategory();
            $category->set_id($category_row->id);
            $category->setName($category_row->name);
            $tagType->setCategory($category);
            self::$cached[$name] = $tagType;
            return $tagType;
        }
        $tagType = new Type();
        $tagType->set_id($row->id);
        $tagType->setName($row->type_name);
        $category = new TypeCategory();
        $category->set_id($row->type_category_id);
        $category->setName($row->type_category_name);
        $tagType->setCategory($category);
        self::$cached[$name] = $tagType;
        return $tagType;
    }
    
    private static function createTypeCategoryTag()
    {
        if (isset(self::$cachedCategoryExists))
            return;
        
        if (!Type_category::where("name", "=", "tag")->exists()) {
            $category = new Type_category();
            $category->name = 'tag';
            $category->save();
        }
        if (!Type_category::where("name", "=", "datablock")->exists()) {
            $category = new Type_category();
            $category->name = 'datablock';
            $category->save();
        }
        self::$cachedCategoryExists = true;
    }
    
    public static function getQueryObject()
    {
        $datatag = \DB::table('types')
            ->leftJoin('type_categories AS category', 'types.type_category_id', '=', 'category.id')
            ->select('types.id AS id', 'types.name AS type_name', 'types.type_category_id AS type_category_id', 'category.name AS type_category_name');
        return $datatag;
    }
    
    /**
     * @return Type
     */
    public static function get_type_property()
    {
        return self::getTypeWithName("property", "tag");
    }
    
    /**
     * @return Type[]
     */
    public static function get_all_tag_types()
    {
        /** @var TypeModel[] $types */
        $types = self::getQueryObject()->where("category.name", "=", "tag")->get();
        $dataTypes = [];
        foreach ($types as $row) {
            $tagType = new Type();
            $tagType->set_id($row->id);
            $tagType->setName($row->type_name);
            $category = new TypeCategory();
            $category->set_id($row->type_category_id);
            $category->setName($row->type_category_name);
            $tagType->setCategory($category);
            array_push($dataTypes, $tagType);
        }
        return $dataTypes;
    }
    
    /**
     * @return Type
     */
    public static function get_type_row()
    {
        return self::getTypeWithName("row", "tag");
    }
    
    /**
     * @return Type
     */
    public static function get_type_column()
    {
        return self::getTypeWithName("column", "tag");
    }
    
    /**
     * @return Type
     */
    public static function get_type_cell()
    {
        return self::getTypeWithName("cell", "datablock");
    }
    
    /**
     * Gets a table cell type for a datablock.
     * @return Type
     */
    public static function get_type_table_cell()
    {
        return self::getTypeWithName("table-cell", "datablock");
    }
    
    /**
     * Gets a table header type for a tag
     * @return Type
     */
    public static function get_type_table_header()
    {
        return self::getTypeWithName("table-cell", "tag");
    }

    /**
     * Gets a table header type for a tag
     * @return Type
     */
    public static function getTagPrimary()
    {
        return self::getTypeWithName("primary", "tag");
    }
    /**
     * Gets a general tag type for a tag
     * @return Type
     */
    public static function getTagGeneral()
    {
        return self::getTypeWithName("general", "tag");
    }
    
    /**
     * @return Type
     */
    public static function get_type_folder()
    {
        return self::getTypeWithName("folder", "tag");
    }
    
    /**
     * Gets all the dynamic types
     * @return array
     */
    public static function get_all_dynamic_types()
    {
        self::createTypeCategoryTag();
        $id = Type_category::where("name", "=", "tag")->first()->id;
        $data_types = TypeModel::where("type_category_id", "=", $id)->all();
        $types = [];
        foreach ($data_types as $data_type)
            array_push($types, $data_type->name);
        return $types;
    }
    
    /**
     * Gets a type by ID
     *
     * @param int $id
     *
     * @return Type
     */
    public static function get_by_id($id)
    {
        if (isset(self::$cachedTypes[$id]))
            return self::$cachedTypes[$id];
        $typemodel = self::getQueryObject();
        $typemodel = self::addWhereID($typemodel, $id);
        $row = $typemodel->first();
        $category = new TypeCategory();
        if (!isset($id)) {
            $test = xdebug_get_function_stack();
            $t = "";
        }
        $category->set_id($row->type_category_id);
        $category->setName($row->type_category_name);
        $type = new Type();
        $type->setCategory($category);
        $type->setName($row->type_name);
        $type->set_id($row->id);
        self::$cachedTypes[$row->id] = $type;
        return $type;
    }
    
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param int                                $id
     *
     * @return \Illuminate\Database\Query\Builder
     */
    private static function addWhereID($query, $id)
    {
        $query->where('types.id', '=', $id);
        return $query;
    }
    
    /**
     * @param String $name
     *
     * @return Type
     */
    public static function create_type_tag($name)
    {
        $type = new Type($name);
        $type->setCategory(TypeCategory::getTagCategory());
        $type->save();
        return $type;
    }
    
    /**
     * Created a new Datablock Type
     *
     * @param String $name
     *
     * @return Type
     */
    public static function create_type_datablock($name)
    {
        $type = new Type($name);
        $type->setCategory(TypeCategory::getDataBlockCategory());
        $type->save();
        return $type;
    }
    
    /**
     * Returns null if none found
     *
     * @param String       $name
     * @param TypeCategory $category
     *
     * @return Type
     */
    public static function get_type_by_name($name, $category)
    {
        $datatype = TypeModel::where("name", "=", $name)->where('type_category_id', "=", $category->get_id())->first();
        if (!isset($datatype))
            return null;
        $type = new Type($name);
        $type->set_id($datatype->id);
        $type->setCategory($category);
        return $type;
    }
    
    /**
     * Gets a Datablock type
     *
     * @param String $name
     *
     * @return Type
     */
    public static function get_type_datablock($name)
    {
        return self::getTypeWithName($name, "datablock");
    }
    
}