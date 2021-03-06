<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/16/2015
 * Time: 9:52 AM
 */

namespace app\libraries\datablocks;

use app\libraries\database\Query;
use app\libraries\datablocks\converter\DataBlockValueConvertor;
use app\libraries\datablocks\DataBlockAbstract;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTags;
use app\libraries\types\Type;
use app\libraries\types\Types;
use App\Models\Data_block;
use App\Models\Tags_reference;
use PDO;

/**
 * Class DataBlock. Used to hold values and manage tags.
 * @package app\libraries\datablocks
 */
class DataBlock extends DataBlockAbstract
{
    
    /**
     * @param TagCollection $tags The tags that the datablock reference
     * @param Type          $type The type to pass to the datablock
     * @param string        $created_at
     * @param string        $updated_at
     */
    public function __construct($tags = null, $type = null, $created_at = null, $updated_at = null, $sort_number = null)
    {
        if (isset($type))
            $this->type = $type;
        if (isset($tags))
            $this->tags = $tags;
        if (isset($created_at))
            $this->created_at = $created_at;
        if (isset($updated_at))
            $this->updated_at = $updated_at;
        if (isset($sort_number))
            $this->sort_number = $sort_number;
    }
    
    /**
     * Sets the type of the datablock
     *
     * @param Type $type
     */
    public function set_type($type)
    {
        $this->type = $type;
    }
    
    /**
     * Sets the tags by Tag Collection
     *
     * @param TagCollection $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
    }
    
    /**
     * @return Type|null
     */
    public function get_type()
    {
        if (!isset($this->id))
            return null;
        if (isset($this->type))
            return $this->type;
        $block = Data_block::where('id', '=', $this->id)->first(['type_id']);
        if (!isset($block))
            return null;
        $this->type = Types::get_by_id($block->type_id);
        return $this->type;
    }
    
    /**
     * Sets the value of the datablock. It can only be a string
     *
     * @param string $value
     */
    public function set_value($value)
    {
        $this->value = $value;
    }
    
    /**
     * Gets the proccessed value of the datablock
     * @return string
     */
    public function getProccessedValue($useMemory = false)
    {
        $converter = new DataBlockValueConvertor($this, $useMemory);
        return $converter->getProcessedValue($this->getValue(), $this->getTags()->getTagWithTypesAsArray([Types::getTagPrimary()])[0]->get_parent_id());
    }
    
    /**
     * Gets the value of the DataBlock
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Gets the collection of tags that are used to identify the datablock
     * @return TagCollection
     */
    public function getTags()
    {
        if(!isset($this->tags))
        {
            $sql = "
                SELECT tags.* FROM tags_reference LEFT JOIN tags ON tags_reference.tag_id = tags.id
                WHERE 
                tags_reference.data_block_id = {$this->id} AND
                tags_reference.deleted_at IS NULL
            ";
            $rows = Query::getPDO()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $this->tags = new TagCollection();
            foreach ($rows as $row)
                $this->tags->add(DataTags::fetchByPDORow($row));
        }
        return $this->tags;
        
    }
    
    /**
     *Creates a datablock in the datablock with the same properties
     * @return bool Returns true if succesful
     */
    public function create()
    {
        if (isset($this->id))
            return false;
        $datablock = new Data_block();
        $datablock->value = $this->value;
        $datablock->type_id = $this->type->get_id();
        $datablock->sort_number = $this->sort_number;
        $datablock->save();
        $this->id = $datablock->id;
        foreach ($this->tags->getAsArray() as $datatag) {
            $reference = new Tags_reference();
            $reference->data_block_id = $this->get_id();
            $reference->tag_id = $datatag->get_id();
            $reference->save();
        }
        return true;
    }
    
    /**
     * Saves the datablock to the database if it already exists
     * @return bool
     */
    public function save()
    {
        if (!isset($this->id))
            return false;
        /** @var Data_block $datablock */
        $datablock = Data_block::where("id", "=", $this->id)->first();
        $datablock->value = $this->value;
        $datablock->type_id = $this->type->get_id();
        $datablock->sort_number = $this->sort_number;
        $datablock->save();
        $this->id = $datablock->id;
        foreach ($this->tags->getAsArray() as $datatag) {
            /**  @var Tags_reference $reference */
            $datablockId = $this->get_id();
            $tagId = $datatag->get_id();
            //checks if the reference exists even if it was in the trash
            $result = Tags_reference::withTrashed()->where('data_block_id','=', $datablockId)->where('tag_id','=', $tagId)->first();
            if (!isset($result)) { // if it does not exist, make the reference
                
                $reference = new Tags_reference();
                $reference->data_block_id = $this->get_id();
                $reference->tag_id = $datatag->get_id();
                $reference->save();
            }
            else if(isset($result->deleted_at)) // if it was deleted but still exists, restore it.
            {
//                $result->restore();
                Tags_reference::withTrashed()->where('data_block_id','=', $datablockId)->where('tag_id','=', $tagId)->restore();
            }
        }
        $otherReferences = Tags_reference::where('data_block_id', '=', $this->get_id())->get(['*']);
        $tagsToSave = $this->tags->getAsArray();
        $tagIdsToSave = [];
        foreach($tagsToSave as $tag)
            $tagIdsToSave[] = $tag->get_id();
        foreach($otherReferences->all() as $reference)
            if(!in_array($reference->tag_id, $tagIdsToSave))
                Tags_reference::where('data_block_id', '=',  $this->get_id())->where('tag_id', '=', $reference->tag_id)->delete();


        return true;
    }
    
    /**
     * Deletes the datablock
     * @return bool true if deleted, false if the datablock does not exist
     */
    public function deleteTagsAndDatablock()
    {
        if (!isset($this->id))
            return false;
        $this->delete();
        foreach ($this->tags->getAsArray() as $tag)
            $tag->delete();
        return true;
    }
    
    /**
     * Deletes the datablock
     * @return bool true if delete successful, false if the datablock has not been created
     */
    public function delete()
    {
        if (!isset($this->id))
            return false;
        Tags_reference::where('data_block_id', '=', $this->get_id())->delete();
        Data_block::where('id', '=', $this->get_id())->delete();
        return true;
    }
    
    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    public function toStdClass()
    {
        $std = new \stdClass();
        $std->id = $this->get_id();
        $std->value = $this->getValue();
        $std->tags = [];
        foreach ($this->getTags()->getAsArray() as $key => $tag)
            $std->tags[$key] = $tag->toStdClass();
        $std->updated_at = $this->updated_at();
        $std->created_at = $this->created_at();
        return $std;
    }
    
    /**
     * Gets the date at when the object was updated.
     * @return String
     */
    public function updated_at()
    {
        if (isset($this->updated_at)) // cached
            return $this->updated_at;
        if (!isset($this->id))
            return null;
        $tag = Data_block::where('id', '=', $this->id)->first(['updated_at']);
        /** Tag $tag **/
        $updated_at = $tag->updated_at;
        $this->updated_at = $updated_at;
        return $updated_at;
    }
    
    /***************************
     * Private methods
     ***************************/
    
    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if (isset($this->created_at)) // cached
            return $this->created_at;
        if (!isset($this->id))
            return null;
        $block = Data_block::where('id', '=', $this->id)->first(['created_at']);
        /** Data_block $block */
        $created_at = $block->created_at;
        $this->created_at = $created_at;
        return $created_at;
    }
    
    /**
     * Checks if the datablock has a duplicate
     *
     * @param $tags
     *
     * @return int|false
     */
    private function check_db_for_duplicate($tags)
    {
        $s_tags = serialize($tags);
        $datablock = Data_block::where("tags", "=", $s_tags)->first();
        if (isset($datablock))
            return $datablock->id;
        else
            return false;
    }
}