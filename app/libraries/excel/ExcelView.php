<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/6/2016
 * Time: 1:50 PM
 */

namespace app\libraries\excel;


use app\libraries\tags\DataTag;
use app\libraries\types\Types;

/**
 * Class ExcelView
 * @package app\libraries\excel
 */
class ExcelView
{
    /**
     * @var DataTag
     */
    public $parentTag;
    /**
     * @var ExcelTable
     */
    public $summaryTable;
    /**
     * @var ExcelSheet
     */
    public $summarySheet;
    /**
     * @var ExcelSheet
     */
    public $summaryHybrid;
    /**
     * @var ExcelView[]
     */
    public $composits = [];

    private $loopedChildren = false;


    /**
     * ExcelView constructor.
     * @param DataTag $tag
     * @param DataTag[]|null $paramHeaders the default headers to use for the header table if no headers where found for the child.
     * @param DataTag[]|null $paramColumns
     */
    function __construct($tag, $paramHeaders = null, $paramColumns = null)
    {
        $this->parentTag = $tag;
        $children = $this->parentTag->getSimpleChildren();
        $composits = $this->parentTag->getCompositChildren();
        $columns = $children->getTagWithTypesAsArray([Types::get_type_column()]);
        if(sizeof($columns) == 0 && is_array($paramColumns))
            $columns = $paramColumns;

        $headers = $children->getTagWithTypesAsArray([Types::get_type_table_header() ]);
        if(sizeof($headers) == 0 && is_array($paramHeaders))
            $headers = $paramHeaders;
        $children->remove([Types::get_type_column(), Types::get_type_table_header()]);
        $rows = $children->getTagWithTypesAsArray([Types::get_type_row()]);
        $children->remove([Types::get_type_row()]);
        
        $this->summaryTable = new ExcelTable($headers);
        $this->summaryHybrid = new ExcelSheet($rows, $headers, $tag);
        $this->summarySheet = new ExcelSheet($rows, $columns, $tag);
        foreach($composits->getAsArray() as $composit)
            array_push($this->composits, new ExcelView($composit,$headers, $columns));

    }
    public function hasChildren()
    {
        return sizeof($this->composits) != 0;
    }

    /**
     * @return bool
     */
    public function hasloopedChildren()
    {
        return $this->loopedChildren;
    }
    public function setLoopedChildren()
    {
        $this->loopedChildren = true;
    }

    /**
     * @return ExcelTab[]
     */
    public function getNavTitles()
    {
        $navTitles = [];

        $stackframes = [ ['index' => 0, 'sheet' => $this]];
        $currentStack = 0;
        while(isset($stackframes[$currentStack]))
        {
            $index = &$stackframes[$currentStack]['index'];
            /**  @var ExcelView $sheet */
            $sheet = &$stackframes[$currentStack]['sheet'];

            if(!isset($sheet->composits[$index])) // if we got to the ned of the loop, pop the stack
            {
                array_push($navTitles, new ExcelTab($this->getNameStack($stackframes, $currentStack), $sheet ));
                unset($stackframes[$currentStack] );
                $currentStack--;
                if(isset($stackframes[$currentStack]))
                    $stackframes[$currentStack]['index']++;
            }
            else
            {
                $currentStack++;
                $stackframes[$currentStack] = ['index' => 0, 'sheet' => $sheet->composits[$index]];
            }

        }
        return array_reverse($navTitles);
    }

    /**
     * @param array $stack
     * @param int $currentStack
     * @return string
     */
    private function getNameStack($stack, $currentStack)
    {
        $name = [];//$sheet->composits[$index]->get_name();
        $imaginaryIndex = $currentStack;
        while($imaginaryIndex >= 0)
        {
            /** @var ExcelView $imaginarySheet */
            $imaginarySheet = $stack[$imaginaryIndex]['sheet'];
            array_unshift($name,$imaginarySheet->get_name() );
            $imaginaryIndex--;
        }
        $name = implode("->",$name);
        return $name;
    }

    public function get_name()
    {
        return $this->parentTag->get_name();
    }



}