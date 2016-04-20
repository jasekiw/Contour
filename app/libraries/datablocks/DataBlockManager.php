<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/4/2016
 * Time: 9:25 AM
 */

namespace app\libraries\datablocks;

use app\libraries\datablocks\DataBlockQueryEngine;
use app\libraries\database\Query;
use app\libraries\datablocks\DataBlock;
use app\libraries\helpers\TimeTracker;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Type;
use app\libraries\types\Types;
use App\Models\Data_block;
use DB;
use PDO;

class DataBlockManager extends DataBlockManagerAbstract
{
    const SELCTION_TYPE_STANDARD = "selectStandard";
    const SELCTION_TYPE_LIGHT = "selectLight";

    /**
     * @param Type $type
     * @return DataBlock[]
     */
    public function getByType($type)
    {
        $collection = array();
        $rows = Data_block::where("type_id", "=", $type->get_id())->get();
        foreach($rows as $row)
        {
            $datatag = DataBlockManager::getByID($row->id);
            if(isset($datatag))
                array_push($collection,$datatag);
        }
        return $collection;
    }

    /**
     * instantiates a datablock by id
     * @param int $id
     * @param boolean $showTrashed
     * @return DataBlock
     */
    public function getByID($id, $showTrashed = false)
    {
        $query = "SELECT * FROM data_blocks WHERE id='" . $id . "' ";
        if(!$showTrashed)
            $query .= "AND deleted_at is NULL";
        $data =  Query::getPDO()->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($data !== false) {

            $tagcollection = new TagCollection();
            $query = "SELECT tag_id FROM tags_reference WHERE data_block_id = '" . $data['id'] . "' AND deleted_at is NULL;";
            $tagIds = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
            foreach($tagIds as $tag_id)
            {
                $datatag = DataTags::get_by_id($tag_id);
                if(isset($datatag))
                    $tagcollection->add($datatag);
            }

            $datablock = new DataBlock($tagcollection, Types::get_by_id($data['type_id']));
            $datablock->set_id($id);
            $datablock->set_value($data['value']);
            return $datablock;
        }
        return null;
    }

    /**
     *  returns a Datablock if found. else returns null
     * @param DataTag[] $dataTags
     * @param string $selectionType
     * @param bool $showTrashed
     * @return DataBlock
     */
    public function getByTagsArray($dataTags, $selectionType = DataBlockManager::SELCTION_TYPE_LIGHT, $showTrashed = false)
    {
        $queryEngine = new DataBlockQueryEngine($dataTags, $showTrashed);
        return $queryEngine->getDataBlock();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function selectStandard($query)
    {
        $query->select([
            "d.id as id",
            "d.value as value",
            "d.type_id as type_id",
            "d.created_at as created_at",
            "d.updated_at as updated_at"
        ]);
    }
    /**
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function selectLight($query)
    {
        $query->select([
            "d.id as id",
            "d.value as value"
        ]);
    }

    /**
     * @param Data_block $data
     * @param DataTag[] $dataTags
     * @return DataBlock|null
     */
    public function getByRow($data, $dataTags )
    {
        if (isset($data))
        {
            $tagcollection = new TagCollection($dataTags);
            $datablock = new DataBlock($tagcollection, Types::get_by_id($data->type_id));
            $datablock->set_id($data->id);
            $datablock->set_value($data->value);
            return $datablock;
        }
        return null;
    }

    /**
     * This function is used to reduce the queries needed to just get the value of a datablock
     * @param DataTag[] $dataTags
     * @param string $selectionType
     * @param bool $showTrashed show trashed items if set to true
     * @return Data_block
     */
    public function getValueByTagsArray($dataTags, $selectionType = DataBlockManager::SELCTION_TYPE_LIGHT, $showTrashed = false)
    {
        $data = self::getQuery($showTrashed);
        $data = self::add_tags_to_query($data, $dataTags);
        self::$selectionType($data);
        $data = $data->first();
        if (!isset($data))
            return null;
        return $data;
    }

    /**
     * @param bool $showtrashed
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery($showtrashed = false){
        $query = \DB::table('data_blocks AS d')
            ->join('tags_reference AS t', 'd.id', '=', 't.data_block_id')
            ->groupBy('d.id');
        if(!$showtrashed)
            $query->whereNull('d.deleted_at')->whereNull('t.deleted_at');
        return $query;
    }

    /**
     * Adds the tags to the query and makes sure they are the only tags
     * @param  \Illuminate\Database\Query\Builder $query
     * @param DataTag[] $dataTags
     * @return \Illuminate\Database\Query\Builder
     */
    public function add_tags_to_query($query, $dataTags)
    {
        $count = 0;
        foreach($dataTags as $datatag)
        {
            if($count == 0)
                $query->where('t.tag_id', '=', $datatag->get_id());
            else
                $query->orWhere('t.tag_id', '=', $datatag->get_id());
            $count++;
        }
        $query->havingRaw('COUNT(*) = ' . $count );
        return $query;
    }

    /**
     * Gets query to include the tags
     * @return  \Illuminate\Database\Query\Builder
     */
    public function getExtendedQuery($showtrashed = false){
        $query = \DB::table('data_blocks AS d')
            ->join('tags_reference AS t', 'd.id', '=', 't.data_block_id')
            ->join('tags', 't.tag_id', '=','tags.id')
            ->groupBy('d.id');
        if(!$showtrashed)
            $query->whereNull('d.deleted_at')->whereNull('t.deleted_at');
        return $query;
    }

    /**
     * Adds Tags to the query and does not check if they are the only tags
     * @param  \Illuminate\Database\Query\Builder $query
     * @param DataTag[] $dataTags
     * @return \Illuminate\Database\Query\Builder
     */
    public function add_optional_tags_to_query($query, $dataTags)
    {
        $count = 0;
        foreach($dataTags as $datatag)
        {
            if($count == 0)
                $query->where('t.tag_id', '=', $datatag->get_id());
            else
                $query->orWhere('t.tag_id', '=', $datatag->get_id());
            $count++;
        }
        return $query;
    }
}