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
use PDO;

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
    private $sort;

    /**
     * DataBlockQueryEngine constructor.
     * @param DataTag[] $dataTags
     * @param bool $showtrashed
     * @param bool $sort
     */
    function __construct($dataTags, $showtrashed = false, $sort = false)
    {
        $this->sort = $sort;
        $this->tags = $dataTags;
        $this->showTrashed = $showtrashed;
    }

    /**
     * @return DataBlock|null
     */
    function getDataBlock()
    {
        $answerDatablock = $this->getRow();

        if(!isset($answerDatablock))
            return null;
        if(sizeof($answerDatablock) == 0)
            return null;
        $answerDatablock = $answerDatablock[0];
        $datablock = new DataBlock(new TagCollection($this->tags),Types::get_by_id($answerDatablock["type_id"]));
        $datablock->set_value($answerDatablock["value"]);
        $datablock->set_id($answerDatablock["id"]);
        return $datablock;
    }

    /**
     * @return array
     */
    private function getRow()
    {
        $size = sizeof($this->tags);
        $query = "";
        $datablockCandidates = [];
        $datablockIDQuery = "";
        $rowsWithOnlyIds = [];
        $answerDatablocks = [];
        if($size == 1)
        {
            $tag = $this->tags[0];
            $query = "SELECT" . " data_block_id as id from tags_reference WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL and ";
            $query .= "tag_id = " . $tag->get_id() . " ";
            $query .= $datablockIDQuery;

            $this->queries .= $query . "<br />";
            $rowsWithOnlyIds = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);

            if(empty($rowsWithOnlyIds))
                return [];
            $datablockIDQuery = "";
            $datablockIDQuery .= "and ( ";
            $sizeOfRows = sizeof($rowsWithOnlyIds);
            foreach($rowsWithOnlyIds as $blockIndex => $block)
            {
                $isLast = $sizeOfRows - $blockIndex == 1;
                $datablockIDQuery .= "data_block_id = " . $block["id"] . " " . ($isLast ? "" : "or ");
            }
            $datablockIDQuery .= ") ";

            $query = "SELECT" ." d.id AS id, d.value AS value, d.type_id AS type_id FROM data_blocks AS d WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL AND ";
            $query .= "d.id = " . $rowsWithOnlyIds[0]["id"];
            $this->queries .= $query . "<br />";
            $rows  = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rows))
                $answerDatablocks = $rows;
        }
        else
        {
            foreach($this->tags as $index => $tag)
            {


                $query = "SELECT" ." data_block_id as id from tags_reference WHERE ";
                if(!$this->showTrashed)
                    $query .= "deleted_at is NULL and ";
                $query .= "tag_id = " . $tag->get_id() . " ";
                $query .= $datablockIDQuery;

                $this->queries .= $query . "<br />";
                $rowsWithOnlyIds = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);

                if(empty($rowsWithOnlyIds))
                    return null;
                $datablockIDQuery = "";
                $datablockIDQuery .= "and ( ";
                $sizeOfRows = sizeof($rowsWithOnlyIds);
                foreach($rowsWithOnlyIds as $blockIndex => $block)
                {
                    $isLast = $sizeOfRows - $blockIndex == 1;
                    $datablockIDQuery .= "data_block_id = " . $block["id"] . " " . ($isLast ? "" : "or ");
                }
                $datablockIDQuery .= ") ";
            }

            $query = "SELECT" ." d.id AS id, d.value AS value, d.type_id AS type_id, d.sort_number as sort_number FROM data_blocks AS d WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL and ";
            $query .= "d.id = " . $rowsWithOnlyIds[0]["id"];
            $this->queries .= $query . "<br />";
            $rows  = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rows))
                $answerDatablocks = $rows;


        }

        return $answerDatablocks;
    }

    /**
     * @return string
     */
    public function getSqlQueries()
    {
        return $this->queries;
    }

    /**
     * @return \app\libraries\datablocks\DataBlock[]
     */
    public function getDataBlocks()
    {
        $answerDatablocks = $this->getRows();
        if(empty($answerDatablocks))
            return [];

        /**
         * @var DataBlock[] $answerDatablocks
         */
        foreach($answerDatablocks as $key => $answerDatablock)
        {
            $datablockRow = $answerDatablocks[$key];
            $answerDatablocks[$key] = new DataBlock(new TagCollection($this->tags),Types::get_by_id($datablockRow["type_id"]));
            $answerDatablocks[$key]->set_value($datablockRow["value"]);
            $answerDatablocks[$key]->set_id($datablockRow["id"]);
            $answerDatablocks[$key]->setSortNumber($datablockRow["sort_number"]);
        }
        return $answerDatablocks;
    }

    /**
     * @return array
     */
    private function getRows()
    {
        $size = sizeof($this->tags);
        $query = "";
        $datablockCandidates = [];
        $datablockIDQuery = "";
        $rowsWithOnlyIds = [];
        $answerDatablocks = [];
        if($size == 1)
        {
            $tag = $this->tags[0];
            $query = "SELECT" ." data_block_id as id from tags_reference WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL and ";
            $query .= "tag_id = " . $tag->get_id() . " ";
            $query .= $datablockIDQuery;

            $this->queries .= $query . "<br />";
            $rowsWithOnlyIds = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);

            if(empty($rowsWithOnlyIds))
                return [];
            $datablockIDQuery = "";
            $datablockIDQuery .= "and ( ";
            $sizeOfRows = sizeof($rowsWithOnlyIds);
            foreach($rowsWithOnlyIds as $blockIndex => $block)
            {
                $isLast = $sizeOfRows - $blockIndex == 1;
                $datablockIDQuery .= "data_block_id = " . $block["id"] . " " . ($isLast ? "" : "or ");
            }
            $datablockIDQuery .= ") ";

            $query = "SELECT" ." d.id AS id, d.value AS value, d.type_id AS type_id, d.sort_number as sort_number FROM data_blocks AS d WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL AND ";
//            $query .= "d.id = " . $rowsWithOnlyIds[0]["id"];
            $query .= "(";
            foreach($rowsWithOnlyIds as $key => $row)
                $query .= ($key == 0 ? "" : "OR ") . "d.id = " . $row["id"] . " ";
            $query .= ") ";
            if($this->sort)
                $query .= "ORDER BY d.sort_number";
            $this->queries .= $query . "<br />";
            $rows  = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rows))
                $answerDatablocks = $rows;
        }
        else
        {
            foreach($this->tags as $index => $tag)
            {
                $query = "SELECT" ." data_block_id as id from tags_reference WHERE ";
                if(!$this->showTrashed)
                    $query .= "deleted_at is NULL and ";
                $query .= "tag_id = " . $tag->get_id() . " ";
                $query .= $datablockIDQuery;

                $this->queries .= $query . "<br />";
                $rowsWithOnlyIds = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);

                if(empty($rowsWithOnlyIds))
                    return [];
                $datablockIDQuery = "";
                $datablockIDQuery .= "and ( ";
                $sizeOfRows = sizeof($rowsWithOnlyIds);
                foreach($rowsWithOnlyIds as $blockIndex => $block)
                {
                    $isLast = $sizeOfRows - $blockIndex == 1;
                    $datablockIDQuery .= "data_block_id = " . $block["id"] . " " . ($isLast ? "" : "or ");
                }
                $datablockIDQuery .= ") ";
            }


            $query = "SELECT" ." d.id AS id, d.value AS value, d.type_id AS type_id FROM data_blocks AS d WHERE ";
            if(!$this->showTrashed)
                $query .= "deleted_at is NULL AND ";
            $query .= "(";
            foreach($rowsWithOnlyIds as $key => $row)
                $query .= ($key == 0 ? "" : "OR ") . "d.id = " . $row["id"] . " ";
            $query .= ")";
            $this->queries .= $query . "<br />";
            $rows  = Query::getPDO()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rows))
                $answerDatablocks = $rows;
        }

        return $answerDatablocks;
    }

}