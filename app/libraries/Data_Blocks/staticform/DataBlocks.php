<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 8/27/2015
 * Time: 1:37 PM
 */

namespace app\libraries\datablocks\staticform;

use app\libraries\datablocks\DataBlock;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Type;
use app\libraries\types\Types;
use App\Models\Data_block;

/**
 * Class DataBlocks: a static implementation of a datablock
 * @package app\libraries\datablocks\staticform
 */
class DataBlocks
{


    /**
     * instantiates a datablock by id
     * @param int $id
     * @return DataBlock
     */
    public static function getByID($id)
    {
        $data = Data_block::where('id', '=', $id)->first();
        if (isset($data)) {

            $tags = $data->tags;
            $tagcollection = new TagCollection();
            $rows = \DB::table('data_blocks AS d')
                ->leftJoin('tags_reference AS t', 'd.id', '=', 't.data_block_id')->where("d.id", "=", $id )->get();

            foreach($rows as $row)
            {
                /** @noinspection PhpUndefinedFieldInspection */
                $datatag = DataTags::get_by_id($row->tag_id);
                if(isset($datatag))
                $tagcollection->add($datatag);
            }

            $datablock = new DataBlock($tagcollection, Types::get_type_cell());
            $datablock->set_id($id);
            $datablock->set_value($data->value);
            return $datablock;
        }
        return null;
    }


    /**
     * @param Type $type
     * @return DataBlock[]
     */
    public static function getByType($type)
    {
        $collection = array();
        $rows = Data_block::where("type_id", "=", $type->get_id())->get();
        foreach($rows as $row)
        {
            $datatag = DataBlocks::getByID($row->id);
            if(isset($datatag))
                array_push($collection,$datatag);
        }
        return $collection;
    }


    /**
     *  returns a Datablock if found. else returns null
     * @param DataTag[] $dataTags
     * @return DataBlock
     */
    public static function getByTagsArray($dataTags)
    {
        $data = self::getQuery();
        $data = self::add_tags_to_query($data, $dataTags);

        $data = $data->first();


        if (isset($data)) {


            $tagcollection = new TagCollection($dataTags);
            $datablock = new DataBlock($tagcollection, Types::get_by_id($data->type_id));
            $datablock->set_id($data->id);
            $datablock->set_value($data->value);
            return $datablock;
        }
        return null;
    }

    public static function getByRow($row)
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
     * @return DataBlock
     */
    public static function getValueByTagsArray($dataTags)
    {





        $data = self::getQuery();
        $data = self::add_tags_to_query($data, $dataTags);

        $data = $data->first();

        if (isset($data)) {
            return  $data->value;
        }
        return null;
    }

    /**
     * @return  \Illuminate\Database\Query\Builder
     */
    public static function getQuery(){
        $datablock = \DB::table('data_blocks AS d')
            ->join('tags_reference AS t', 'd.id', '=', 't.data_block_id')

            ->groupBy('d.id');
        return $datablock;
    }


    /**
     * Gets query to include the tags
     * @return  \Illuminate\Database\Query\Builder
     */
    public static function getExtendedQuery(){
        $datablock = \DB::table('data_blocks AS d')
            ->join('tags_reference AS t', 'd.id', '=', 't.data_block_id')
            ->join('tags', 't.tag_id', '=','tags.id')
            ->groupBy('d.id');
        return $datablock;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @param DataTag[] $dataTags
     * @return \Illuminate\Database\Query\Builder
     */
    public static function add_tags_to_query($query, $dataTags)
    {
        $count = 0;
        foreach($dataTags as $datatag)
        {
            if($count == 0)
            {
                $query->where('t.tag_id', '=', $datatag->get_id());
            }
            else
            {
                $query->orWhere('t.tag_id', '=', $datatag->get_id());
            }

//            $query->havingRaw('SUM(t.tag_id = ' . $datatag->get_id() . ') = 1');
            $count++;
        }
        $query->havingRaw('COUNT(*) = ' . $count );
        return $query;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder $query
     * @param DataTag[] $dataTags
     * @return \Illuminate\Database\Query\Builder
     */
    public static function add_optional_tags_to_query($query, $dataTags)
    {
        $count = 0;
        foreach($dataTags as $datatag)
        {
            if($count == 0)
            {
                $query->where('t.tag_id', '=', $datatag->get_id());
            }
            else
            {
                $query->orWhere('t.tag_id', '=', $datatag->get_id());
            }

//            $query->havingRaw('SUM(t.tag_id = ' . $datatag->get_id() . ') = 1');
            $count++;
        }

        return $query;
    }














}