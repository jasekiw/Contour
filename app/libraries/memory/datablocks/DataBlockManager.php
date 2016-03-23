<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/4/2016
 * Time: 9:41 AM
 */

namespace app\libraries\memory\datablocks;

use app\libraries\datablocks\DataBlockManagerAbstract;
use app\libraries\memory\datablocks\DataBlock;
use app\libraries\memory\MemoryDataManager;
use app\libraries\memory\tags\DataTag;
use app\libraries\memory\types\Type;
use App\Models\Data_block;

/**
 * Class DataBlockManager
 * @package app\libraries\memory\datablocks
 */
class DataBlockManager extends DataBlockManagerAbstract
{
    private $manager;


    /**
     * DataBlockManager constructor.
     * @param MemoryDataManager $manager
     */
    function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $id
     * @return DataBlock
     */
    public function getByID($id)
    {
        return isset($this->manager->datablocks[$id]) ? $this->manager->datablocks[$id] : null;
    }

    /**
     * @param Type $type
     * @return DataBlock[]
     */
    public function getByType($type)
    {
        if(isset($this->manager->datablocksByType[$type->get_id()]))
            return $this->manager->datablocksByType[$type->get_id()];
        return [];
    }

    /**
     *  returns a Datablock if found. else returns null
     * @param DataTag[] $dataTags
     * @return DataBlock
     */
    public function getByTagsArray($dataTags)
    {
       $arguments = [];
        foreach($dataTags as $i => $tag)
            $arguments[] = $this->manager->referencesByTagId[$tag->get_id()];
        $result = call_user_func_array('array_intersect',$arguments);
        if(!empty($result))
            return array_shift($result);
        return null;
    }

    /**
     * This function is used to reduce the queries needed to just get the value of a datablock
     * @param DataTag[] $dataTags
     * @return Data_block
     */
    public function getValueByTagsArray($dataTags)
    {
        // TODO: Implement getValueByTagsArray() method.
    }
}