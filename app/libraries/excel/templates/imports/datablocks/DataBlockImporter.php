<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 1:48 PM
 */
namespace app\libraries\excel\templates\imports\Datablocks;
use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use \app\libraries\excel\Formula\TagConverter;
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

        $count = 0;
        $type = Types::get_type_cell();
        $total = Data_block::where("type_id", "=", $type->get_id())->selectRaw("count(*)")->get()->first()["count(*)"];

        $limit = 1000;
        $offset = 0;
        $blocks = Data_block::where("type_id", "=", $type->get_id())->limit($limit)->offset($offset)->get();
        while(!$blocks->isEmpty())
        {
            foreach($blocks as $row)
            {

                $dataBlock = DataBlocks::getByID($row->id);
                $converter = new TagConverter();
                $oldValue = $dataBlock->getValue();
                $converter->get_tag_value($dataBlock);
                //$dataBlock->save(); // not ready yet



                echo "<br />finished datablock: " . $count . " out of " . $total . " ID: " . $dataBlock->get_id() ."<br />";
                echo "Old value: " . $oldValue . " &nbsp;&nbsp;&nbsp;&nbsp; New Value: " . $dataBlock->getValue() . "<br />";
                $count++;


                flush();
//                if($count > 50)
//                {
//                    exit;
//                }
                \UserMeta::save('progress', $count . '/' . $total);
            }
            $offset += $limit;
            $blocks = Data_block::where("type_id", "=", $type->get_id())->limit($limit)->offset($offset)->get();
        }





    }
}

