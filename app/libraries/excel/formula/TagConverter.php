<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/8/2015
 * Time: 2:04 PM
 */
namespace app\libraries\excel\Formula;
use app\libraries\datablocks\DataBlock;
use app\libraries\excel\formula\conversion\FormulaConversion;

/**
 * Class TagConverter
 * @package app\libraries\excel\Formula
 */
class TagConverter {

    /**
     * @param DataBlock $datablock
     * @return String
     */
    public function get_tag_value($datablock)
    {
        $converter = new FormulaConversion();

        $newValue = $converter->ConvertToTagFormat($datablock);
        $datablock->set_value($newValue);
        return $newValue;
    }
} 