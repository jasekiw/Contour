<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:23 AM
 */

namespace app\libraries\memory\tags;


use app\libraries\memory\MemoryDataManager;
use app\libraries\memory\tags\DataTag as MemoryTag;
use app\libraries\tags\collection\TagCollectionAbstract;
use app\libraries\tags\DataTag;

/**
 * Class TagCollection
 * @package app\libraries\memory\tags
 */
class TagCollection extends TagCollectionAbstract
{

    /**
     * @return DataTag[]
     */
    public function getColumnsAsArray()
    {
        /**
         * TODO: fix types array to allow the same name twice
         */
         $column = null;
        if(isset(MemoryDataManager::getInstance()->typesByName["column"]))
            $column = MemoryDataManager::getInstance()->typesByName["column"];
        $columns = [];
        foreach($this->tags as $tag)
            if($tag->get_type()->get_id() == $column->get_id())
                $columns[] = $tag;
        return $columns;
    }

    /**
     * @return DataTag[] | MemoryTag[]
     */
    public function getRowsAsArray()
    {
        $row = null;
        if(isset(MemoryDataManager::getInstance()->typesByName["row"]))
            $row = MemoryDataManager::getInstance()->typesByName["row"];
        $rows = [];
        foreach($this->tags as $tag)
            if($tag->get_type()->get_id() == $row->get_id())
                $rows[] = $tag;
        return $rows;
    }
}