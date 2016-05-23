<?php
namespace app\libraries\excel;

use app\libraries\datablocks\DataBlockCollection;
use app\libraries\datablocks\DataBlock;
use app\libraries\tags\DataTag;
use app\libraries\types\Types;
use app\libraries\tags\collection\TagCollection;
use View;

/**
 * Class ExcelView
 * @package app\libraries\excel
 */
class ExcelView
{
    
    /** @var DataTag   */
    public $parentTag;
    /** @var  DataTag[] */
    public $headerTags;
    /** @var ExcelView[]   */
    public $composits = [];
    /**
     * @var DataTag[][]
     */
    private $rowTags = [];
    /**
     * @var DataBlock[][]
     */
    private $rows = [];
    private $orientation = "column";
    
    private $loopedChildren = false;
    const ORIENTATION_COLUMN = "column";
    const ORIENTATION_ROW = "row";
    const ORIENATION  = "orientation";

    
    /**
     * ExcelView constructor.
     *
     * @param DataTag $tag
     */
    function __construct($tag)
    {
        $this->parentTag = $tag;
        $this->orientation = $this->parentTag->getMetaValue(self::ORIENATION); // row  or column // the location that primary tags are displayed
        if($this->orientation == "")
        {
            $this->orientation = self::ORIENTATION_COLUMN;
            $this->parentTag->setMetaValue(self::ORIENATION, $this->orientation);
        }
        $children = $this->parentTag->getSimpleChildren();
        $composits = $this->parentTag->getCompositChildren()->getAsArray(TagCollection::SORT_TYPE_NONE);
        $headers = $children->getTagWithTypesAsArray([Types::getTagPrimary()]);
        /** @var DataBlock[][] $columns */
        $columns = [];
        usort($headers, function($x,$y){
            /** @var DataTag $x */
            /** @var DataTag $y */
            return $x->get_sort_number() - $y->get_sort_number();
        });
        $this->headerTags = $headers;
        /** @var DataTag[] $headers The headers Tags */
        foreach($headers as $header)
            $columns[$header->get_sort_number()] = $header->getDataBlocks()->getAssociativeArrayOfSortNumber(); // sort numbers are row numbers
        ksort($columns);
        $rows = [];
        /** Invert the rows and columns */
        foreach($columns as $colSort => $column)
            foreach($column as $rowSort => $rowDataBlock)
            {
                if(!isset($rows[$rowSort]))
                    $rows[$rowSort] = [];
                $rows[$rowSort][$colSort] = $rowDataBlock;
            }
        $rowTags = [];
        ksort($rows);
        foreach($rows as $rowNum => $row)
            $rowTags[$rowNum] = (new DataBlockCollection($row))->getCommonTags()->getAsArray(TagCollection::SORT_TYPE_NONE);
        $this->rowTags = $rowTags;
        $this->rows = $rows;
        foreach($composits as $composit)
            $this->composits[] = new ExcelView($composit);
    }

    /**
     * @param int $x
     * @param int $y
     * @return DataBlock | null
     */
    public function getCell(int $x, int $y)
    {
        if(isset($this->rows[$y][$x]))
            return $this->rows[$y][$x];
        else return null;
    }

    /**
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @return DataTag[]
     */
    public function getHeaderTags()
    {
        return $this->headerTags;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param $rowNum
     * @return DataTag[]
     */
    public function getTagsForRow($rowNum)
    {
        if(!isset($this->rowTags[$rowNum]))
            return [];
        return $this->rowTags[$rowNum];
    }

    /**
     * @param int $rowNum
     * @return string
     */
    public function getCommaDelimitedTagsForRow($rowNum)
    {
        if(!isset($this->rowTags[$rowNum]))
            return "";
        $ids = [];
        foreach($this->rowTags[$rowNum] as $tag)
            $ids[] = $tag->get_id();
        return implode(",", $ids);
    }

    /**
     * @return bool
     */
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

    /**
     * @return ExcelTab[]
     */
    public function getNavTitles()
    {
        $navTitles = [];
        
        $stackframes = [['index' => 0, 'sheet' => $this]];
        $currentStack = 0;
        while (isset($stackframes[$currentStack])) {
            $index = &$stackframes[$currentStack]['index'];
            /**  @var ExcelView $sheet */
            $sheet = &$stackframes[$currentStack]['sheet'];
            
            if (!isset($sheet->composits[$index])) // if we got to the ned of the loop, pop the stack
            {
                array_push($navTitles, new ExcelTab($this->getNameStack($stackframes, $currentStack), $sheet));
                unset($stackframes[$currentStack]);
                $currentStack--;
                if (isset($stackframes[$currentStack]))
                    $stackframes[$currentStack]['index']++;
            } else {
                $currentStack++;
                $stackframes[$currentStack] = ['index' => 0, 'sheet' => $sheet->composits[$index]];
            }
        }
        return array_reverse($navTitles);
    }
    
    /**
     * @param array $stack
     * @param int   $currentStack
     *
     * @return string
     */
    private function getNameStack($stack, $currentStack)
    {
        $name = [];//$sheet->composits[$index]->get_name();
        $imaginaryIndex = $currentStack;
        while ($imaginaryIndex >= 0) {
            /** @var ExcelView $imaginarySheet */
            $imaginarySheet = $stack[$imaginaryIndex]['sheet'];
            array_unshift($name, $imaginarySheet->get_name());
            $imaginaryIndex--;
        }
        $name = implode("->", $name);
        return $name;
    }

    /**
     * Renders the table
     * @return string
     */
    public function render()
    {
        if($this->orientation == self::ORIENTATION_COLUMN)
            return View::make('partials.excel.sheet', ['sheet' => $this])->render();
        else
            return View::make('partials.excel.sheetflipped', ['sheet' => $this])->render();
    }
    
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->parentTag->get_name();
    }

    /**
     * @return DataTag
     */
    public function getParentTag()
    {
        return $this->parentTag;
    }
    
}