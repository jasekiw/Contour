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
class ExcelSheet
{

    private $containsData = false;
    /**
     * @var DataTag[]
     */
    private $columns = [];
    /**
     * @var DataTag[]
     */
    private $rows = [];
    /**
     * @var DataBlock[][]
     */
    private $cells = [];
    private $tagsById = [];
    /**
     * @var DataTag
     */
    private $parentTag;

    /**
     * Sets the tags and grabs the datablocks
     * @param DataTag[] $rows
     * @param DataTag[] $columns
     */
    function __construct($rows, $columns, $parentTag)
    {
        $this->parentTag = $parentTag;
        $this->tags = [];
        $y = 0;
        $this->rows = array_values($rows);
        $this->columns = array_values($columns);
        foreach($this->rows as $row)
        {

            if(!isset($this->cells[$y]))
                $this->cells[$y] = [];
            $x = 0;
            foreach($this->columns as $column)
            {
                $datablock = DataBlocks::getByTagsArray(array($row,$column ));
                if(isset($datablock))
                    $this->containsData = true;
                if(!isset($this->cells[$y][$x]))
                    $this->cells[$y][$x] = $datablock;
                $x++;
            }
            $y++;
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
    public function hasData()
    {
        return $this->containsData;
    }

    /**
     * @param $x
     * @param $y
     * @return DataBlock|null
     */
    public function getCell($x, $y)
    {
        if(isset($this->cells[$y][$x]))
            return $this->cells[$y][$x];
        return null;
    }

    /**
     * @return DataTag
     */
    public function getParentTag()
    {
        return $this->parentTag;
    }

}