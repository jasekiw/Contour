<?php

namespace App\Http\Controllers\api;

use app\libraries\Data_Blocks\formula\Parser;
use app\libraries\database\DataManager;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiDataBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * @param $id
     */
    public function save($id)
    {
        $value = \Input::get("value");
        $datablock = DataBlocks::getByID($id);
        $datablock->set_value($value);
        $datablock->save();
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
        foreach($ids as $index => $id)
            $tags[$index] = DataTags::get_by_id($id);



        $datablock = DataBlocks::getByTagsArray($tags);
        $stdDataBlock = new \stdClass();
        if(isset($datablock))
        {
            $stdDataBlock->id = $datablock->get_id();
            $parser = new Parser(DataManager::getInstance());
            $context = $datablock->getTags()->getRowsAsArray()[0]->get_parent_id();
            $stdDataBlock->value = $parser->parse($datablock->getValue(), $context);
           // $stdDataBlock->value = $datablock->getProccessedValue();
        }
        else
        {
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
