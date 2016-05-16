<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 11:49 PM
 */

namespace app\libraries\excel;

use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;

/**
 * Class ExcelProperties
 * @package app\libraries\excel
 */
class ExcelProperties extends ExcelData
{
    
    /** @var DataTag[]   */
    private $propertyTags;
    /** @var DataBlock[]   */
    private $values = [];
    protected $templateName = "Properties";
    /**
     * ExcelProperties constructor.
     *
     * @param DataTag[] $propertyTags
     * @param           $parentTag
     */
    function __construct($propertyTags, $parentTag)
    {
        $this->propertyTags = array_values($propertyTags);
        $this->parentTag = $parentTag;
        foreach ($this->propertyTags as $key => $tag)
            $this->values[$key] = DataBlocks::getByTagsArray([$tag]);
    }
    
    /**
     * @return \app\libraries\tags\DataTag[]
     */
    public function getProperyTags()
    {
        return $this->propertyTags;
    }
    
    /**
     * @param int $y
     *
     * @return DataBlock |null
     */
    public function getPropertyValue($y)
    {
        if (isset($this->values[$y]))
            return $this->values[$y];
        return null;
    }
    
}