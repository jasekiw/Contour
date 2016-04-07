<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 11:16 PM
 */

namespace app\libraries\excel;

use app\libraries\tags\DataTag;

abstract class ExcelData
{
    /**
     * @var bool
     */
    protected $containsData = false;
    /**
     * @var DataTag
     */
    protected $parentTag;

    /**
     * Check if this template has data in it.
     * @return bool
     */
    public function hasData()
    {
        return $this->containsData;
    }
    /**
     * @return DataTag
     */
    public function getParentTag()
    {
        return $this->parentTag;
    }
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->parentTag->get_name();
    }
}