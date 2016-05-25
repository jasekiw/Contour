<?php

namespace App\Http\Controllers\api;

use app\libraries\ajax\AjaxResponse;
use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\formula\Parser;
use app\libraries\database\DataManager;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;
use Illuminate\Http\Request;
use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DataBlockController extends Controller
{

    /**
     * POST /api/datablocks/save
     *{
     *  id: int
     *  value : string
     *}
     * @param $id
     * @return string
     */
    public function save($id = null)
    {
        $response = new AjaxResponse();
        $response->fail("unkown failure");
        if (!isset($id))
            $id = Input::get('id');
        $value = Input::get("value");
        $datablock = DataBlocks::getByID($id);
        if (isset($datablock)) {
            $datablock->set_value($value);
            $datablock->save();
            $response->success("saved sucessfully");
            $response->setPayload($datablock->toStdClass());
        } else
            $response->fail("datablock does not exist");

        return $response->send();
    }

    public function create()
    {
        $response = new AjaxResponse();
        $response->fail("unkown failure");
        $tagIds = Input::get('tagIds');
        $value = Input::get('value');
        $typeName = Input::get('type');
        $sort_number = Input::get('sort_number', 0);
        if (!$response->reliesOnMany(["tagIds" => $tagIds, "value" => $value, "type" => $typeName]))
            return $response->send();
        if (!is_array($tagIds)) {
            $response->fail("tagIds is not an array.");
            return $response->send();
        }
        $dataTags = [];
        foreach ($tagIds as $id) {
            $dataTag = DataTags::get_by_id($id);
            if (!isset($dataTag)) {
                $response->fail("datatag id " . $id . " is not found");
                return $response->send();
            } else
                array_push($dataTags, $dataTag);
        }
        $type = Types::get_type_by_name($typeName, TypeCategory::getDataBlockCategory());
        if (!isset($type)) {
            $response->fail("tag name was not found");
            return $response->send();
        }

        $datablock = new DataBlock(new TagCollection($dataTags), $type);
        $datablock->set_value($value);
        $datablock->setSortNumber($sort_number);
        $datablock->create();
        $response->setPayload($datablock->toStdClass());
        $response->success("succesfully created");
        return $response->send();
    }

    /**
     * @return string
     */
    public function getByTagIds()
    {
        /** @var int[] $ids */
        $ids = \Input::get("tags");
        /** @var DataTag[] $tags */
        $tags = array();
        foreach ($ids as $index => $id)
            $tags[$index] = DataTags::get_by_id($id);

        $datablock = DataBlocks::getByTagsArray($tags);
        $stdDataBlock = new \stdClass();
        if (isset($datablock)) {
            $stdDataBlock->id = $datablock->get_id();
            $parser = new Parser(DataManager::getInstance());
            $context = $datablock->getTags()->getRowsAsArray()[0]->get_parent_id();
            $stdDataBlock->value = $parser->parse($datablock->getValue(), $context);
            // $stdDataBlock->value = $datablock->getProccessedValue();
        } else {
            $stdDataBlock->id = -1;
            $stdDataBlock->value = "";
        }

        $stdDataBlock->type = "block";
        $response = new \stdClass();

        $response->datablock = $stdDataBlock;
        $response->success = true;
        return json_encode($response);
    }
    

}
