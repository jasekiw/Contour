<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/30/2016
 * Time: 2:56 PM
 */

namespace app\libraries\excel\import\exception;


use app\libraries\excel\import\SheetImporter;

class ParentNotFoundException extends ExcelImportException
{

    /**
     * SheetNotFoundException constructor.
     * @param string $column
     * @param int $row
     * @param SheetImporter $importObject
     */
    function __construct($column, $row, $importObject)
    {
        $code = 0;
        $nl = "<br />";
        $importObjectState = print_r($importObject, true);
        parent::__construct("Canot find parent. column: " . $column . " row: " . $row . $this->nl . $importObjectState, null);
    }
}