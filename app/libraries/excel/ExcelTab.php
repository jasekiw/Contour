<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/6/2016
 * Time: 3:51 PM
 */

namespace app\libraries\excel;

/**
 * Class ExcelTab
 * @package app\libraries\excel
 */
class ExcelTab
{
    
    /** @var string $name   */
    public $name;
    /** @var ExcelView   */
    public $excelView;
    
    function __construct($name, $sheet)
    {
        $this->name = $name;
        $this->excelView = $sheet;
    }
    
    public function getCodeName()
    {
        return str_replace(">", "", $this->name);
    }
}