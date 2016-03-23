<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/4/2016
 * Time: 11:49 AM
 */

namespace app\libraries\database;


use app\libraries\datablocks\DataBlockManager;
use app\libraries\tags\DataTagManager;

/**
 * Class DataManager
 * @package app\libraries\database
 */
class DataManager
{
    private static $memoryObject;
    public $dataTagManager;
    public $dataBlockManager;


    /**
     * MemoryDataManager constructor.
     */
    public function __construct()
    {
        $this->dataTagManager = new DataTagManager($this);
        $this->dataBlockManager = new DataBlockManager($this);
    }

    /**
     * @return DataManager
     */
    public static function getInstance()
    {
        if(!isset(self::$memoryObject))
            self::$memoryObject  = new DataManager();
        return self::$memoryObject;
    }
}