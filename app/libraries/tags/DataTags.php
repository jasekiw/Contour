<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 8/25/2015
 * Time: 1:35 PM
 * DataTags are the static form of a DataTag use for instansiation
 */
namespace app\libraries\tags;
use app\libraries\tags\collection\TagCollection;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;
use App\Models\Tag;
use \app\libraries\types\Type;
use App\Models\TypeModel;
use App\Models\Type_category;

/**
 * Class DataTags: The static class used to manage a tags
 * @package app\libraries\tags
 */
class DataTags
{



    /**
     * Gets a type object by id
     * @param int $id
     * @return Type Type
     */
    public static function get_type_by_id($id)
    {
        return Types::get_by_id($id);

    }

    /**
     * Gets a type row id by the name.
     * Retuns null if none found.
     * @param String $name
     * @return int
     */
    public static function get_type_id_by_string($name)
    {
        return TypeModel::where("name", "=", $name )->exists() ? TypeModel::where("name", "=", $name )->first()->id : null;
    }


    /**
     * Gets a datatag object by the row id. return null if none found.
     * @param int $id
     * @return DataTag
     */
    public static function get_by_id($id)
    {
        /** @var Tag $data_tag */
        $data_tag = self::getDataTagsQueryBuilder();
        $data_tag = $data_tag->where('tags.id', '=', $id)->first();
        return self::get_by_row($data_tag);
    }


    /**
     * Gets a TagCollection of all the tags with the given parent id
     * @param int $id
     * @return TagCollection
     */
    public static function get_by_parent_id($id)
    {
        $collection = new TagCollection();
        $data_tags = Tag::where("parent_tag_id", "=", $id )->get();
        foreach($data_tags as $data_tag)
        {
            $tag = DataTags::get_by_id($data_tag->id);
            $collection->add($tag);
        }
        return $collection;
    }



    /**
     * @requires a row is already picked from the Query
     * @param \Illuminate\Database\Eloquent\Model $data_tag
     * @return \app\libraries\tags\DataTag
     */
    public static function get_by_row($data_tag)
    {
        if( isset($data_tag) )
        {
            $tag = new DataTag();
            $tag->set_name($data_tag->name);
            if(isset($data_tag->type_name))
            {
                $type = new Type();
                $type->set_id($data_tag->type_id);
                $type->setName($data_tag->type_name);
                if(isset($data_tag->type_category_name))
                {
                    $category = new TypeCategory();
                    $category->set_id($data_tag->type_category_id);
                    $category->setName( $data_tag->type_category_name);
                    $type->setCategory($category);
                }
                $tag->set_type($type);

            }

            $tag->set_parent_id($data_tag->parent_tag_id);


            $tag->set_sort_number($data_tag->sort_number);
            $tag->set_id($data_tag->id);

            return $tag;
        }
        return null;


    }

    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @param integer $parent_id Optional
     * @return DataTag
     */
    public static function get_by_string( $text, $parent_id = null)
    {

        $query = self::getDataTagsQueryBuilder();
        $text = str_replace(" ", "_", $text);
        $tag = $query->where("tags.name", "=", $text );
         if(isset($parent_id))
             $tag->where("parent_tag_id", "=", $parent_id);

        $tag = $tag->first();

        if( isset($tag) )
        {
            $datatag = DataTags::get_by_row($tag);
            return $datatag;
        }

        return null;
    }




    /**
     * Gets the Query object for gathering datablock information
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getDataTagsQueryBuilder()
    {
        //TODO: create this query to be correct
        $datatag = \DB::table('tags')
            ->leftJoin('types', 'tags.type_id', '=', 'types.id')
            ->leftJoin('type_categories as type_category', 'types.type_category_id', '=', 'type_category.id')


            ->select('tags.id as id',
                'tags.name as name',
                'tags.type_id as type_id',
                'tags.parent_tag_id as parent_tag_id',
                'tags.sort_number as sort_number',
                'types.name as type_name',
                'type_category.name as type_category_name',
                'type_category.id as type_category_id' );
        return $datatag;
    }



    /**
     * Use this function only if you do not have the datatag object. This is slower than calling findChildBySortNumber
     * @param integer $sort_number
     * @param Type $type
     * @param integer $parent_id
     * @return DataTag Will also return null if nothing found
     */
    public static function get_by_sort_id( $sort_number,  $type,  $parent_id)
    {

        $parent = DataTags::get_by_id($parent_id);
        $datatag = $parent->findChildBySortNumber($sort_number, $type);
        return $datatag;

    }




    /**
     * @param String|integer $nameorID
     * @param integer|null $parentID
     * @return bool Exists
     */
    public static function exists($nameorID, $parentID = null)
    {
        if(strtoupper(gettype($nameorID)) == "INTEGER")
        {

            return Tag::where("id", "=", $nameorID)->exists();

        }
        else
        {
            if(isset($parentID))
            {
                if(DataTags::get_by_string($nameorID, $parentID) != null)
                {
                    return true;
                }
                else
                {
                    return false;
                }

            }
            else
            {
                return false;
            }

        }
    }

    public function createTagTypeCategory()
    {
        if(!Type_category::where('name', '=', 'tag')->exists())
        {
            $typeCategory = new Type_category();
            $typeCategory->name = 'tag';
            $typeCategory->save();
        }
    }



}