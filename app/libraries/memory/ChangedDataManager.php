<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/3/2016
 * Time: 9:27 AM
 */

namespace app\libraries\memory;

use app\libraries\database\DatabaseObject;

/**
 * Class ChangedDataManager
 * @package app\libraries\memory
 */
class ChangedDataManager
{
    
    const TYPE_TAGS = "tags";
    const TYPE_TAGS_REFERENCE = "tags_reference";
    const TYPE_TYPES = "types";
    const TYPE_TYPE_CATEGORIES = "type_categories";
    const TYPE_DATA_BLOCKS = "data_blocks";
    private static $tags = [];
    private static $tags_reference = [];
    private static $types = [];
    private static $type_categories = [];
    private static $data_blocks = [];
    
    /**
     * @param ChangedProperty $changedProperty
     */
    public static function addToChanged($changedProperty)
    {
        $arrayName = $changedProperty->table;
        if (!isset(self::$$arrayName[$changedProperty->sourceObject->get_id()]))
            self::$$arrayName[$changedProperty->sourceObject->get_id()] = [];
        self::$$arrayName[$changedProperty->sourceObject->get_id()][$changedProperty->databaseProperty] = $changedProperty;
    }
    
    /**
     * @param ChangedProperty $changedProperty
     */
    public static function removeFromChanged($changedProperty)
    {
        $arrayName = $changedProperty->table;
        if (isset(self::$$arrayName[$changedProperty->sourceObject->get_id()]))
            unset(self::$$arrayName[$changedProperty->sourceObject->get_id()][$changedProperty->databaseProperty]);
    }
    
    /**
     * Removes all changes from being saved.
     *
     * @param DataBaseObject $object
     * @param string         $type
     */
    public static function removeObjectFromChanged($object, $type)
    {
        if (isset(self::$$type[$object->get_id()]))
            unset(self::$$type[$object->get_id()]);
    }
    
    /**
     *  Gets the changed data for the specified table.
     *
     * @param string $type
     * [ChangedDataManager::TYPE_TAGS |
     * ChangedDataManager::TYPE_TAGS_REFERENCE |
     * ChangedDataManager::TYPE_TYPES |
     * ChangedDataManager::TYPE_TYPE_CATEGORIES |
     * ChangedDataManager::TYPE_DATA_BLOCKS]
     *
     * @return ChangedProperty[][]
     */
    public static function getChangedData($type)
    {
        return self::$$type;
    }
}