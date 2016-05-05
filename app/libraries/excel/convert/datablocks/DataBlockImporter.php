<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 1:48 PM
 */
namespace app\libraries\excel\convert\datablocks;

use app\libraries\async\AsyncJob;
use \app\libraries\excel\Formula\TagConverter;
use app\libraries\memory\MemoryDataManager;
use app\libraries\tags\DataTagManager;
use app\libraries\theme\system\System;
use app\libraries\types\Types;
use App\Models\Data_block;

/**
 * Class DataBlockImporter
 * @package app\libraries\excel\templates\imports\Datablocks
 */
class DataBlockImporter
{
    
    /**
     * DataBlockImporter constructor.
     */
    function __construct()
    {
    }
    
    /**
     * Converts the values to actual references to tags
     */
    function runImport()
    {
        
        $type = Types::get_type_cell();
        $limit = 1000;
        $offset = 0; // starting location
        $count = $offset;
        $total = Data_block::where("type_id", "=", $type->get_id())->selectRaw("count(*)")->get()->first()["count(*)"];
        $numCpus = System::num_cpus();
        $count = 0;
        
        $jobs = [];
        while ($offset < $total) {
            $task = new AsyncJob();
            $data = new \stdClass();
            $data->offset = $offset;
            
            if (($offset + $limit) < $total)
                $data->length = $limit;
            else
                $data->length = $total - $offset;
            
            $task->setData($data);
            $task->setJob("convertdatablocks");
            $task->activateJob();
            $jobs[] = $task;
            //sleep(5);
            $offset += $limit;
            
            while (sizeof($jobs) >= $numCpus) {
                /* @var AsyncJob[] $jobs */
                foreach ($jobs as $index => $job)
                    if ($job->isComplete())
                        unset($jobs[$index]);
                sleep(2);
            }
        }
        
        /**
         * @param DataTagManager | \app\libraries\memory\tags\DataTagManager $dataTagManager
         */
        function runImportSingleThreaded($dataTagManager)
        {
            
            $manager = MemoryDataManager::getInstance();
            $blockmanager = $manager->dataBlockManager;
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            $type = Types::get_type_cell();
            $total = Data_block::where("type_id", "=", $type->get_id())->selectRaw("count(*)")->get()->first()["count(*)"];
            
            $limit = 1000;
            $offset = 0; // starting location
            $count = $offset;
            $blocks = Data_block::where("type_id", "=", $type->get_id())->limit($limit)->offset($offset)->get();
            while (!$blocks->isEmpty()) {
                foreach ($blocks as $row) {
                    
                    $dataBlock = $blockmanager->getByID($row->id);
                    $converter = new TagConverter();
                    $oldValue = $dataBlock->getValue();
                    $converter->get_tag_value($dataBlock);
                    //$dataBlock->save(); // not ready yet
                    
                    echo "<br />finished datablock: " . $count . " out of " . $total . " ID: " . $dataBlock->get_id() . "<br />";
                    echo "Old value: " . $oldValue . " &nbsp;&nbsp;&nbsp;&nbsp; New Value: " . $dataBlock->getValue() . "<br />";
                    $count++;
                    
                    flush();
                    if ($count % 50 == 0) {
                        \UserMeta::save('progress', $count . '/' . $total);
                    }
                }
                
                $offset += $limit;
                $blocks = Data_block::where("type_id", "=", $type->get_id())->limit($limit)->offset($offset)->get();
            }
        }
    }
}

