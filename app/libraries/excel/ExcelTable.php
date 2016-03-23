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
class ExcelTable
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
     */
    function __construct($headers)
    {
        $this->headers = array_values($headers);
        $x = 0;
        foreach($this->headers as $header)
        {
            $table_cells[$x] = DataBlocks::getMultipleByTagsArray(array($header ),
                    DataBlocks::SELCTION_TYPE_LIGHT, false, true)->getByTypes([Types::get_type_table_cell()]);
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

}