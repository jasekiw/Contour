<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/7/2015
 * Time: 2:34 PM
 */

namespace app\libraries\Data_Blocks\converter;


use app\libraries\Data_Blocks\formula\Parser;
use app\libraries\datablocks\DataBlock;

/**
 * Class DataBlockValueConvertor
 * @package app\libraries\Data_Blocks\converter
 */
class DataBlockValueConvertor
{
    /**
     * @var DataBlock
     */
    private $datablock;
    /**
     * DataBlockValueConvertor constructor.
     */
    public function __construct($datablock = null)
    {
        $this->datablock = $datablock;
    }


    /**
     * @return string
     */
    public function getProcessedValue($value)
    {
        $parser = new Parser($this->datablock);
        return $parser->parse($value);

    }

}