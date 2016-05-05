<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/30/2016
 * Time: 11:16 AM
 */

namespace app\libraries\excel\import\exception;

use Exception;

/**
 * Class SheetNotFoundException
 * @package app\libraries\excel\import\exception
 */
class SheetNotFoundException extends ExcelImportException
{
    
    /**
     * SheetNotFoundException constructor.
     *
     * @param string $sheet_num  The sheet number
     * @param int    $sheetTitle The Sheet Title
     */
    function __construct($sheet_num, $sheetTitle)
    {
        $code = 0;
        parent::__construct("Sheet not found Sheet Number: " . $sheet_num . " Sheet Title: " . $sheetTitle, $code, null);
    }
}