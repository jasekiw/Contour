<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 2/2/2016
 * Time: 9:09 AM
 */

namespace app\libraries\database;

use DB;
use PDO;

/**
 * Class Query
 * @package app\libraries\database
 */
class Query
{
    
    /** @var PDO   */
    private static $PDO;
    
    /**
     * Gets the Default PDO Object
     * @return PDO
     */
    public static function getPDO()
    {
        if (self::$PDO === null)
            self::$PDO = DB::connection()->getPdo();
        return self::$PDO;
    }
}