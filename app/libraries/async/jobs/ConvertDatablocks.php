<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/17/2015
 * Time: 11:55 AM
 */

namespace app\libraries\async\jobs;

use app\libraries\async\AsyncJobAbstract;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\excel\Formula\TagConverter;
use app\libraries\types\Types;
use App\Models\Data_block;
use App\Models\Async_Job;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;


/**
 * Class ConvertDatablocks
 * @package app\libraries\async\jobs
 */
class ConvertDatablocks extends AsyncJobAbstract
{
    /**
     * Returns the name of the job
     * @return string
     */
    public static function getName()
    {
        return "convertdatablocks";
    }

    /**
     * @param \stdClass $data
     * @return mixed|void
     */
    public function handle($data)
    {
        $this->turnOnErrorReporting();
        $this->log("starting Job");
        $offset = intval( $data['offset']);
        $limit = intval( $data['length']);
        if($limit == 0)
            $this->markErrorAndExit("limit is set to 0, data not passed correctly perhaps?");
        $this->setProgressMax($limit);
        $type = Types::get_type_cell();
        $total = Data_block::where("type_id", "=", $type->get_id())->selectRaw("count(*)")->get()->first()["count(*)"];
        $count = 0;
        $blocks = Data_block::where("type_id", "=", $type->get_id())->limit($limit)->offset($offset)->get();
        foreach($blocks as $row)
        {
            $dataBlock = DataBlocks::getByID($row->id);
            $converter = new TagConverter();
            $oldValue = $dataBlock->getValue();
            $converter->get_tag_value($dataBlock);
            $dataBlock->save(); // gonna give it a go!
            $this->log("finished datablock: " . $count . " out of " . $total . " ID: " . $dataBlock->get_id() ."\r\n" .
              "Old value: " . $oldValue . "      New Value: " . $dataBlock->getValue() . "\r\n");
            $count++;
            if($count % 5 == 0)
                $this->saveProgress($count);
        }
        $this->saveProgress($count);
    }
}