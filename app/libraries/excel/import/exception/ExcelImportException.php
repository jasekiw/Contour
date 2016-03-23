<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/30/2016
 * Time: 2:43 PM
 */

namespace app\libraries\excel\import\exception;


use Exception;

class ExcelImportException extends Exception
{
    protected $nl = "\r\n";
}