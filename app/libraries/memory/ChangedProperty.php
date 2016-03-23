<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 9:28 AM
 */

namespace app\libraries\memory;
use app\libraries\database\DatabaseObject;


/**
 * Class ChangedProperty
 * @package app\libraries\memory
 */
class ChangedProperty
{
    /**
     * The property name in the database
     * @var string
     */
    public $databaseProperty;
    /**
     * The function to call to get that property on the memory object
     * @var string
     */
    public $propertyToGet;
    /**
     * The Table name for the changed property
     * @var string
     */
    public $table;
    /**
     * @var DatabaseObject
     */
    public $sourceObject;

}