<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 1/28/2016
 * Time: 5:38 PM
 */

namespace app\libraries\Data_Blocks;


use app\libraries\database\Query;
use app\libraries\datablocks\DataBlock;
use app\libraries\helpers\TimeTracker;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\types\Types;
use DB;
/**
 * Class DataBlockQueryEngine
 * @package app\libraries\Data_Blocks
 */
class DataBlockQueryEngine
{
    private $query;
    /**
     * @var \app\libraries\tags\DataTag[]
     */
    private $tags;
    private $incremental = false;
    private $showTrashed = false;
    private $queries = "";
    /**
     * DataBlockQueryEngine constructor.
     * @param DataTag[] $dataTags
     * @param bool $showtrashed
     */
    function __construct($dataTags, $showtrashed = false)
    {
        $this->tags = $dataTags;
//        if(sizeof($dataTags) > 1)
//            $this->incremental = true;
        $this->showTrashed = $showtrashed;
    }

    function getDataBlock()
    {
        $size = sizeOf($this->tags);
        $query = "";
        $datablockCandidates = [];
        $datablockIDQuery = "";
        $rowsWithOnlyIds = array();
        $answerDatablock = null;
        foreach($this->tags as $index => $tag)
        {
            if($index == ($size -1)) // last index
            {
                $query = "SELECT d.id AS id, d.value AS value, d.type_id AS type_id FROM data_blocks AS d WHERE ";
                if(!$this->showTrashed)
                    $query .= "deleted_at is NULL and ";
                $query .= "d.id = " . $rowsWithOnlyIds[0]["id"];
                $this->queries .= $query . "<br />";
                $rows  = Query::getPDO()->query($query)->fetchAll();
                if(!empty($rows))
                    $answerDatablock = $rows[0];

            }
            else
            {
                $query = /** @lang MySQL */
                    "SELECT data_block_id as id from tags_reference WHERE ";
                if(!$this->showTrashed)
                    $query .= "deleted_at is NULL and ";
                $query .= "tag_id = " . $tag->get_id() . " ";
                $query .= $datablockIDQuery;

                $this->queries .= $query . "<br />";
                $rowsWithOnlyIds = Query::getPDO()->query($query)->fetchAll();

                if(empty($rowsWithOnlyIds))
                    return null;
                $datablockIDQuery = "";
                $datablockIDQuery .= "and ( ";
                $sizeOfRows = sizeOf($rowsWithOnlyIds);
                foreach($rowsWithOnlyIds as $blockIndex => $block)
                {
                    $isLast = $sizeOfRows - $blockIndex == 1;
                    $datablockIDQuery .= "data_block_id = " . $block["id"] . " " . ($isLast ? "" : "or ");
                }
                $datablockIDQuery .= ") ";
            }
        }
        if(!isset($answerDatablock))
            return null;
        $datablock = new DataBlock(new TagCollection($this->tags),Types::get_by_id($answerDatablock["type_id"]));
        $datablock->set_value($answerDatablock["value"]);
        $datablock->set_id($answerDatablock["id"]);
        return $datablock;
    }
    public function getSqlQueries()
    {
        return $this->queries;
    }

}