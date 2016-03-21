<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 8/25/2015
 * Time: 1:35 PM
 * DataTags are the static form of a DataTag use for instansiation
 */
namespace app\libraries\tags;
use app\libraries\database\Query;
use app\libraries\helpers\TimeTracker;
use app\libraries\tags\collection\TagCollection;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;
use App\Models\Tag;
use \app\libraries\types\Type;
use App\Models\TypeModel;
use App\Models\Type_category;
use DB;
use PDO;

/**
 * DEPRECATED use the DataTagManager Instead
 * Class DataTags: The static class used to manage a tags
 * @package app\libraries\tags
 */
class DataTags
{

    /**
     * @var PDO
     */
    private static $PDO = null;
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


//    public static function get_by_id($id)
//    {
//        $timeTracker = new TimeTracker();
//        $timeTracker->startTimer('get_by_id');
//        /** @var \Illuminate\Database\Query\Builder $data_tag */
//        $data_tag = self::getDataTagsQueryBuilder();
//        $data_tag = $data_tag->where('tags.id', '=', $id)->first();
//        $datatag = self::get_by_row($data_tag);
//        $timeTracker->stopTimer('get_by_id');
//        $timeTracker->getResults();
//        exit;
//        return $datatag;
//    }

    /**
     * Gets a datatag object by the row id. return null if none found.
     * @param int $id
     * @param bool $showTrashed
     * @return DataTag
     */
    public static function get_by_id($id, $showTrashed = false)
    {
        self::checkPDO();
        $query = "SELECT * FROM tags WHERE id = '" . $id . "' ";
        if(!$showTrashed)
            $query .= "AND deleted_at is NULL";
        $sqlTag = self::$PDO->query($query)->fetch(PDO::FETCH_ASSOC);
        if($sqlTag === false)
            return null;
        return self::fetchByPDORow($sqlTag);
    }

    private static function checkPDO()
    {
        if(self::$PDO == null)
            self::$PDO = Query::getPDO();
    }


    /**
     * Gets a TagCollection of all the tags with the given parent id
     * @param int $id
     * @return TagCollection
     */
    public static function get_by_parent_id($id)
    {
        $collection = new TagCollection();
        if($id == -1)
            $id = 0;
        $data_tags = Tag::where("parent_tag_id", "=", $id )->get();
        foreach($data_tags as $data_tag)
        {
            $id = $data_tag->id;
            $tag = DataTags::get_by_id($data_tag->id);
            $collection->add($tag);
        }
        return $collection;
    }

    /**
     * @requires a row is already picked from the Query
     * @param \App\Models\Tag $data_tag
     * @return \app\libraries\tags\DataTag
     */
    public static function get_by_row($data_tag)
    {
        if(!isset($data_tag))
            return null;

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

    /**
     * @requires a row is already picked from the Query
     * @param string[]
     * @return \app\libraries\tags\DataTag
     */
    private static function fetchByPDORow($sqlTag)
    {

        $tag = DataTag::constructWithTimestamp($sqlTag["updated_at"], $sqlTag["created_at"]);
        $tag->set_name($sqlTag['name']);
        $tag->set_type_id((int)$sqlTag['type_id']);
        $tag->set_parent_id($sqlTag['parent_tag_id']);
        $tag->set_sort_number($sqlTag['sort_number']);
        $tag->set_id($sqlTag['id']);
        return $tag;
    }

//    /**
// * Gets a Datatag by the name and parent id.
// * @param String $text
// * @param integer $parent_id Optional
// * @return DataTag
// */
//    public static function get_by_string( $text, $parent_id = null)
//    {
//        $timer = new TimeTracker();
//        $timer->startTimer("bystringQuery");
//        $query = self::getDataTagsQueryBuilder();
//        $text = str_replace(" ", "_", $text);
//        $tag = $query->where("tags.name", "=", $text );
//
//        if(isset($parent_id) && $parent_id !== -1)
//            $tag->where("parent_tag_id", "=", $parent_id);
//        else if($parent_id === 0 || $parent_id === -1)
//        {
//            $tag->Where(function ($query) { // advanced where statement to fix the issue where the tag.name was included as an optional where
//                foreach(\Contour::getConfigManager()->getPathTags() as $index => $id)
//                {
//                    if($index === 0)
//                        $query->where("parent_tag_id", "=", $id);
//                    else
//                        $query->orwhere("parent_tag_id", "=", $id);
//                }
//            });
//
//        }
//        $tag = $tag->first();
//
//        if( isset($tag) )
//        {
//            $tag = DataTags::get_by_row($tag);
//            $timer->stopTimer("bystringQuery");
//            $timer->getResults();
//            exit;
//            return $tag;
//        }
//
//        $timer->stopTimer("bystringQuery");
//        $timer->getResults();
//        exit;
//        return null;
//    }


    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @param integer $parent_id Optional
     * @param bool $showTrashed
     * @return DataTag
     */
    public static function get_by_string( $text, $parent_id = null, $showTrashed = false)
    {
        $query = "SELECT id as id, name as name, type_id as type_id, sort_number as sort_number FROM tags WHERE name = ? ";
        $text = DataTags::validate_name($text);

        if(!$showTrashed)
            $query .= "AND deleted_at is NULL ";

        if(isset($parent_id) && $parent_id !== -1)
            $query .= "AND parent_tag_id = " . $parent_id . " ";
        else if($parent_id === 0 || $parent_id === -1)
        {
            $pathTags = \Contour::getConfigManager()->getPathTags();
            if(!empty($pathTags))
            {
                $query .= "AND ( ";
                $pathSize = sizeOf($pathTags);
                foreach($pathTags as $index => $id)
                {
                    if($index == ($pathSize - 1) )
                        $query .= "parent_tag_id = " . $id . " ";
                    else
                        $query .= "parent_tag_id = " . $id . " OR ";
                }
                $query .= ") ";
            }
        }
        $peparedStatement = Query::getPDO()->prepare($query);
        //$peparedStatement->bindParam(':name', $text);
        $peparedStatement->execute([$text]);
        $tag = $peparedStatement->fetch();

        if($tag !== false )
        {
            $dataTag = new DataTag($tag["name"],$parent_id, Types::get_by_id($tag["type_id"]), $tag["sort_number"]);
            $dataTag->set_id($tag["id"]);
            return $dataTag;
        }

        return null;
    }

    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @return DataTag[]
     */
    public static function get_multiple_by_string( $text)
    {
        $query = self::getDataTagsQueryBuilder();
        $text = str_replace(" ", "_", $text);
        $tagQuery = $query->where("tags.name", "=", $text );
        $tags = $tagQuery->get();
        $realTags = array();
        /** @var Tag[] $tags*/
        foreach($tags as $tag)
            $realTags[] = DataTags::get_by_row($tag);
        return $realTags;
    }

    /**
     * Gets a tag that matches the given name and the children tree
     * @param $name
     * @param $children
     * @return DataTag|null
     */
    public static function getTagByChildren($name, $children)
    {
        $tags = self::get_multiple_by_string($name);
        foreach($tags as $tag)
        {
            $currentChild = null;
            foreach($children as $index => $child)
            {
                if($index == 0)
                    $currentChild = $tag->findChild($children[0]);
                if($currentChild === null)
                    break;
                $currentChild = $currentChild->findChild($child);
                if($currentChild === null)
                    break;
                if($index == (sizeOf($children) - 1)) // last child
                    return $tag;
            }
        }
        return null;
    }

    /**
     * @param string $text
     * @param Type $type
     * @param int $parent_id
     * @return DataTag|null
     */
    public static function get_by_string_and_type($text, $type, $parent_id = null)
    {
        $query = self::getDataTagsQueryBuilder();
        $text = str_replace(" ", "_", $text);
        $tag = $query->where("tags.name", "=", $text );
        $tag->where("type_id", "=", $type->get_id());
        if(isset($parent_id))
            $tag->where("parent_tag_id", "=", $parent_id);
        else if($parent_id === 0 || $parent_id === -1)
        {
            $tag->Where(function ($query) { // advanced where statement to fix the issue where the tag.name was included as an optional where
                /** @var \Illuminate\Database\Query\Builder $query */
                foreach(\Contour::getConfigManager()->getPathTags() as $index => $id)
                {
                    if($index === 0)
                        $query->where("parent_tag_id", "=", $id);
                    else
                        $query->orwhere("parent_tag_id", "=", $id);
                }
            });

        }
        $tag = $tag->first();
        if( isset($tag) )
            return DataTags::get_by_row($tag);
        return null;
    }


    /**
     * Removes unworthy characters from the tag Identifier
     * @param string $name The string to validate
     * @return string
     */
    public static function validate_name($name)
    {
        $name = preg_replace('!\s+!', '_', $name);
       // $name = str_replace(" ", "_", $name);
        $name = str_replace("'", "", $name);
        $name = str_replace("\\", "", $name);
        $name = str_replace("/", "", $name);
        $name = str_replace("/", "", $name);
        $name = str_replace("(", "", $name);
        $name = str_replace(")", "", $name);
        return $name;
    }

    /**
     * Gets the Query object for gathering datablock information
     * @param bool $getTrashed set true if you want trashed tags
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getDataTagsQueryBuilder($getTrashed = false)
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
            if(!$getTrashed)
                $datatag->whereNull('tags.deleted_at');
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
            return Tag::where("id", "=", $nameorID)->exists();
        if(!isset($parentID))
            return false;
        if(DataTags::get_by_string($nameorID, $parentID) !== null)
            return true;
        return false;
    }

    public function createTagTypeCategory()
    {
        if(Type_category::where('name', '=', 'tag')->exists())
            return;
        $typeCategory = new Type_category();
        $typeCategory->name = 'tag';
        $typeCategory->save();
    }



}