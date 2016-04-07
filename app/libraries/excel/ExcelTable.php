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
use app\libraries\types\Types;

/**
 * Class ExcelTable
 * @package app\libraries\excel
 */
class ExcelTable extends ExcelData
{
    /**
     * @var DataTag[]
     */
    private $headers = [];

    /**
     * @var DataBlock[][]
     */
    private $table_cells = [];

    /**
     * ExcelTable constructor.
     * @param DataTag[] $headers
     * @param DataTag $parentTag
     */
    function __construct($headers, $parentTag)
    {
        $this->parentTag = $parentTag;
        $this->headers = array_values($headers);
        $x = 0;
        foreach($this->headers as $header)
        {
            $this->table_cells[$x] = DataBlocks::getMultipleByTagsArray(array($header ),
                    DataBlocks::SELCTION_TYPE_LIGHT, false, true)->getByTypes([Types::get_type_table_cell()]);
            if(sizeof($this->table_cells[$x]) > 0)
                $this->containsData = true;
            $x++;
        }
    }


    /**
     * @return DataTag[]
     */
    public function getheaders()
    {
        return $this->headers;
    }

    /**
     * @return DataBlock[][]
     */
    public function getCells()
    {
        return $this->table_cells;
    }

    /**
     * @param int $x
     * @param int $y
     * @return DataBlock | null
     */
    public function getCell($x, $y)
    {
        if(isset($this->table_cells[$x][$y]))
            return $this->table_cells[$x][$y];
        return null;
    }

    /**
     * Checks to see if the row has data
     * @param int $y
     * @return bool
     */
    public function rowHasData($y)
    {
        foreach($this->table_cells as $row)
            if(isset($row[$y]) && !empty($row[$y]))
                return true;
        return false;
    }


}