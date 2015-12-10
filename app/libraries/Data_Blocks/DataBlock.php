<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/16/2015
 * Time: 9:52 AM
 */

namespace app\libraries\datablocks;

use app\libraries\Data_Blocks\converter\DataBlockValueConvertor;
use app\libraries\database\DatabaseObject;
use app\libraries\tags\collection\TagCollection;
use app\libraries\types\Type;
use App\Models\Data_block;
use Exception;
use App\Models\Tags_reference;

/**
 * Class DataBlock. Used to hold values and manage tags.
 * @package app\libraries\datablocks
 */
class DataBlock extends DatabaseObject{


    /**
     * @var TagCollection
     * @access private
     */
    private $tags = null;
    /**
     * @var string
     * @access private
     */
    private $value = "";
    /**
     * @var Type
     * @access private
     */
    private $type = null;


    /**
     * @param TagCollection $tags
     * @param Type $type
     */
    public function __construct($tags = null,$type = null)
    {
        if(isset($type))
        {
            $this->type = $type;
        }
        if(isset($tags))
        {
            $this->tags = $tags;
        }

    }



    /**
     * Sets the type of the datablock
     * @param Type $type
     */
    public function set_type($type)
    {
       $this->type = $type;
    }



    /**
     * Sets the tags by Tag Collection
     * @param TagCollection $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Sets the value of the datablock. It can only be a string
     * @param string $value
     */
    public function set_value($value)
    {
        $this->value = $value;
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
     * Gets the proccessed value of the datablock
     * @return string
     */
    public function getProccessedValue()
    {
        $converter = new DataBlockValueConvertor($this);
        return $converter->getProcessedValue($this->getValue());
    }





    /**
     *Creates a datablock in the datablock with the same properties
     * @return bool Returns true if succesful
     */
    public function create()
    {

        try
        {
            if(!isset($this->id) )
            {
                $datablock = new Data_block();
                $datablock->value = $this->value;
                $datablock->type_id = $this->type->get_id();
                $datablock->save();
                $this->id = $datablock->id;
                foreach($this->tags->getAsArray() as $datatag)
                {
                    $reference = new Tags_reference();
                    $reference->data_block_id = $this->get_id();
                    $reference->tag_id = $datatag->get_id();
                    $reference->save();
                }
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(\Exception $e)
        {

            \Kint::dump($this);
            \Kint::trace();
            return false;
        }
    }




    /**
     * Saves the datablock to the database if it already exists
     * @return bool
     */
    public function save()
    {
        if(isset($this->id) )
        {
            /**
             * @var Data_block $datablock
             */
            $datablock = Data_block::where("id", "=", $this->id)->first();
            $datablock->value = $this->value;
            $datablock->type_id = $this->type->get_id();
            $datablock->save();
            $this->id = $datablock->id;
            foreach($this->tags->getAsArray() as $datatag)
            {
                /**  @var Tags_reference $reference */
                $reference = Tags_reference::where('data_block_id', '=', $this->get_id())->where('tag_id', '=', $datatag->get_id())->first();
                if(!isset($reference))
                {

                    $reference = new Tags_reference();
                    $reference->data_block_id = $this->get_id();
                    $reference->tag_id = $datatag->get_id();
                    $reference->save();
                }
                else
                {
                    $reference->data_block_id = $this->get_id();
                    $reference->tag_id = $datatag->get_id();
                    $reference->save();
                }

            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Gets the collection of tags that are used to identify the datablock
     * @return TagCollection
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Gets the date at when the object was updated.
     * @return String
     */
    public function updated_at()
    {
        if(isset($this->updated_at)) // cached
        {
            return $this->updated_at;
        }

        if(isset($this->id))
        {
            $tag = Data_block::where('id', '=', $this->id)->first();
            /**
             * \Tag $tag
             */
            $updated_at = $tag->updated_at;
            $this->updated_at = $updated_at;
            return $updated_at;
        }
        else
        {
            return null;
        }
    }

    /**
     * Gets the date at when the object was created
     * @return String
     */
    public function created_at()
    {
        if(isset($this->created_at)) // cached
        {
            return $this->created_at;
        }

        if(isset($this->id))
        {
            $block = Data_block::where('id', '=', $this->id)->first();
            /**
             * \Data_block $block
             */
            $created_at = $block->created_at;
            $this->created_at = $created_at;
            return $created_at;
        }
        else
        {
            return null;
        }
    }



    /***************************
     *
     *
     * Private methods
     *
     *
     *
     ***************************/


    /**
     * Checks if the datablock has a duplicate
     * @param $tags
     * @return int|false
     */
    private function check_db_for_duplicate($tags)
    {
        $s_tags = serialize($tags);
        $datablock = Data_block::where("tags", "=", $s_tags)->first();
        if(isset($datablock))
        {
            return $datablock->id;
        }
        else
        {
            return false;
        }
    }



}