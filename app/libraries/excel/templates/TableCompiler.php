<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/6/2016
 * Time: 11:34 AM
 */

namespace app\libraries\excel\templates;


use app\libraries\datablocks\staticform\DataBlocks;
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

    /**
     * TableCompiler constructor.
     * @param $id
     */
    function __construct($id)
    {
        $facility = DataTags::get_by_id($id); // gets the facility tag
        $this->name = $facility->get_name();
        $summary= $facility->getSimpleChildren();
        $composits = $facility->getCompositChildren();



        $columns = $summary->getTagWithTypeAsArray(Types::get_type_column());
        $summary->remove(Types::get_type_column());
        $summaryBlocks = array();
        foreach($summary->getAsArray() as $tag)
        {
            foreach($columns as $column)
            {
                $datablock = DataBlocks::getByTagsArray(array($tag,$column ));
                array_push($summaryBlocks, $datablock);
            }
        }


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
        $this->summaryTable = new TableBuilder($summary->getAsArray(), $columns, $summaryBlocks, "summaryTable" );
        $this->compositTables = $compositTables;
    }

}