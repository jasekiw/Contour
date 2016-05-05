<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/4/2016
 * Time: 9:37 AM
 */

namespace app\libraries\datablocks;

use app\libraries\tags\DataTagAbstract;
use app\libraries\types\TypeAbstract;
use App\Models\Data_block;

/**
 * Class DataBlockManagerAbstract
 * @package app\libraries\datablocks
 */
abstract class DataBlockManagerAbstract
{
    
    /**
     * @param $id
     *
     * @return mixed
     */
    public abstract function getByID($id);
    
    /**
     * @param TypeAbstract $type
     *
     * @return DataBlock[]
     */
    public abstract function getByType($type);
    
    /**
     *  returns a Datablock if found. else returns null
     *
     * @param DataTagAbstract[] $dataTags
     *
     * @return DataBlock
     */
    public abstract function getByTagsArray($dataTags);
    
    /**
     * This function is used to reduce the queries needed to just get the value of a datablock
     *
     * @param DataTagAbstract[] $dataTags
     *
     * @return Data_block
     */
    public abstract function getValueByTagsArray($dataTags);
    
}