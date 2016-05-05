<?php
///**
// * Created by PhpStorm.
// * User: Jason
// * Date: 1/6/2016
// * Time: 11:34 AM
// */
//
///**
// * Deprecated - no longer will be used
// */
//
//namespace app\libraries\excel\templates;
//
//use app\libraries\datablocks\staticform\DataBlocks;
//use app\libraries\excel\ExcelSheet;
//use app\libraries\excel\ExcelTable;
//use app\libraries\helpers\TimeTracker;
//use app\libraries\tags\collection\TagCollection;
//use app\libraries\tags\DataTags;
//use app\libraries\theme\data\TableBuilder;
//use app\libraries\types\Types;
//
///**
// * Class TableCompiler
// * @package app\libraries\excel\templates
// */
//class TableCompiler
//{
//
//    public $name;
//    public $summary;
//    public $summaryBlocks;
//    public $columns;
//    public $compositTags;
//    public $summaryTable;
//    public $compositTables;
//    public $sheet;
//
//    /**
//     * TableCompiler constructor.
//     *
//     * @param $id
//     */
//    function __construct($id)
//    {
//
//        $facility = DataTags::get_by_id($id); // gets the facility tag
//        $this->sheet = $facility;
//        $this->name = $facility->get_name();
//
//        $summary = $facility->getSimpleChildren();
//
//        $composits = $facility->getCompositChildren();
//
//        $columns = $summary->getTagWithTypesAsArray([Types::get_type_column()]);
//        $headers = $summary->getTagWithTypesAsArray([Types::get_type_table_header()]);
//        $summary->remove([Types::get_type_column(), Types::get_type_table_header()]);
//        $rows = $summary->getTagWithTypesAsArray([Types::get_type_row()]);
//        $summary->remove([Types::get_type_row()]);
//
//        $summaryTable = new ExcelTable($headers);
//        $summarySheetWithTableHeaders = new ExcelSheet($rows, $headers);
//        $summarySheet = new ExcelSheet($rows, $columns);
//
//        $compositsBlocks = [];
//        $compositChildrenTags = [];
//        $compositTables = [];
//        foreach ($composits->getAsArray() as $compositTag) {
//            $compositsBlocks[] = [];
//            $compositChildrenTags[] = [];
//            $children = $compositTag->get_children_recursive();
//            array_push($compositChildrenTags[sizeof($compositChildrenTags) - 1], $children->getAsArray());
//            foreach ($children->getAsArray() as $child) {
//                foreach ($columns as $column) {
//                    $datablock = DataBlocks::getByTagsArray([$child, $column]);
//                    array_push($compositsBlocks[sizeof($compositsBlocks) - 1], $datablock);
//                }
//            }
//            array_push($compositTables,
//                new TableBuilder($children->getAsArray(), $columns, $compositsBlocks[sizeof($compositsBlocks) - 1], $compositTag->get_id(), $compositTag->get_name()));
//        }
//        $this->summary = $summary;
//        $this->summaryBlocks = $summaryBlocks;
//        $this->columns = $columns;
//        $this->compositTags = $composits->getAsArray();
//        $this->summaryTable = new TableBuilder($summary->getAsArray(), $columns, $summaryBlocks, $facility->get_id(), "summaryTable");
//        $this->compositTables = $compositTables;
//    }
//
//}