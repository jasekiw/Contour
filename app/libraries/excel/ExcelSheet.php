<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/5/2016
 * Time: 4:30 PM
 */

namespace app\libraries\excel;

use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;

/**
 * Class ExcelSheet
 * @package app\libraries\excel
 */
class ExcelSheet extends ExcelData
{
    
    /** @var DataTag[]   */
    private $columns = [];
    /** @var DataTag[]   */
    private $rows = [];
    /** @var DataBlock[][]   */
    private $cells = [];
    protected $templateName = "Sheet Data";
    /**
     * Sets the tags and grabs the datablocks
     *
     * @param DataTag[] $rows
     * @param DataTag[] $columns
     * @param DataTag   $parentTag
     */
    function __construct($rows, $columns, $parentTag)
    {
        $this->parentTag = $parentTag;
        
        $this->rows = array_values($rows);
        $this->columns = array_values($columns);
        if(!empty($rows) && !empty($columns) )
            $this->containsData = true;
        foreach ($this->rows as $y => $row) {
            if (!isset($this->cells[$y]))
                $this->cells[$y] = [];
            foreach ($this->columns as $x => $column) {
                $datablock = DataBlocks::getByTagsArray([$row, $column]);
                if (isset($datablock))
                    $this->containsData = true;
                if (!isset($this->cells[$y][$x]))
                    $this->cells[$y][$x] = $datablock;
            }
        }
    }
    
    /**
     * @return DataTag[]
     */
    public function getRowTags()
    {
        return $this->rows;
    }
    
    /**
     * @return DataTag[]
     */
    public function getColumnTags()
    {
        return $this->columns;
    }
    
    /**
     * Gets the cells that were found.
     * @return DataBlock[][]
     */
    public function getCells()
    {
        return $this->cells;
    }
    
    /**
     * @param $x
     * @param $y
     *
     * @return DataBlock|null
     */
    public function getCell($x, $y)
    {
        if (isset($this->cells[$y][$x]))
            return $this->cells[$y][$x];
        return null;
    }
    
}