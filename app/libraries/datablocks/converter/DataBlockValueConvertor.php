<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/7/2015
 * Time: 2:34 PM
 */

namespace app\libraries\Data_Blocks\converter;


use app\libraries\Data_Blocks\formula\Parser;
use app\libraries\database\DataManager;
use app\libraries\datablocks\DataBlock;
use app\libraries\memory\MemoryDataManager;

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
    private $useMemory;

    /**
     * DataBlockValueConvertor constructor.
     * @param DataBlock $datablock
     * @param bool $useMemory
     */
    public function __construct($datablock = null, $useMemory = false)
    {
        $this->useMemory = $useMemory;
        $this->datablock = $datablock;
    }


    /**
     * @return string
     */
    public function getProcessedValue($value, $context)
    {
        $parser= null;
        if($this->useMemory)
            $parser = new Parser( MemoryDataManager::getInstance());
        else
            $parser = new Parser( DataManager::getInstance());
        return $parser->parse($value, $context);

    }

}