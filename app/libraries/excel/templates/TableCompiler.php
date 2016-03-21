<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/6/2016
 * Time: 11:34 AM
 */

namespace app\libraries\excel\templates;


use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\helpers\TimeTracker;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTags;
use app\libraries\theme\data\TableBuilder;
use app\libraries\types\Types;

/**
 * Class TableCompiler
 * @package app\libraries\excel\templates
 */
class TableCompiler
{
    public $name;
    public $summary;
    public $summaryBlocks;
    public $columns;
    public $compositTags;
    public $summaryTable;
    public $compositTables;
    public $sheet;

    /**
     * TableCompiler constructor.
     * @param $id
     */
    function __construct($id)
    {
//        $timer = new TimeTracker();
        $facility = DataTags::get_by_id($id); // gets the facility tag
        $this->sheet = $facility;
        $this->name = $facility->get_name();
//        $timer->startTimer("summary");
        $summary= $facility->getSimpleChildren();
//        $timer->stopTimer("summary");
//        $timer->startTimer("composits");
        $composits = $facility->getCompositChildren();
//        $timer->stopTimer("composits");


//        $timer->startTimer("summartDataBlocks");
        $columns = $summary->getTagWithTypeAsArray(Types::get_type_column());
        $summary->remove(Types::get_type_column());
        $summaryBlocks = array();
        $count = 0;
        foreach($summary->getAsArray() as $tag)
        {
            foreach($columns as $column)
            {
//                $timer->startTimer("grabbingDataBlock" . $count);
                $datablock = DataBlocks::getByTagsArray(array($tag,$column ));
//                $timer->stopTimer("grabbingDataBlock" . $count);
                array_push($summaryBlocks, $datablock);
                $count++;
            }
        }
//        $timer->stopTimer("summartDataBlocks");
//        $timer->getResults();
//        exit;

        $compositsBlocks = [];
        $compositChildrenTags = [];
        $compositTables = [];
        foreach($composits->getAsArray() as $compositTag)
        {
            $compositsBlocks[] = [];
            $compositChildrenTags[] = [];
            $children = $compositTag->get_children_recursive();
            array_push($compositChildrenTags[sizeOf($compositChildrenTags) - 1], $children->getAsArray());
            foreach($children->getAsArray() as $child)
            {
                foreach($columns as $column)
                {
                    $datablock = DataBlocks::getByTagsArray(array($child,$column ));
                    array_push($compositsBlocks[sizeOf($compositsBlocks) - 1], $datablock);
                }
            }
            array_push($compositTables,
                new TableBuilder($children->getAsArray(), $columns, $compositsBlocks[sizeOf($compositsBlocks) - 1],$compositTag->get_id(), $compositTag->get_name() ));

        }
        $this->summary = $summary;
        $this->summaryBlocks = $summaryBlocks;
        $this->columns = $columns;
        $this->compositTags = $composits->getAsArray();
        $this->summaryTable = new TableBuilder($summary->getAsArray(), $columns, $summaryBlocks,$facility->get_id(), "summaryTable" );
        $this->compositTables = $compositTables;
    }

}