<?php

namespace App\Http\Controllers;
use App\Models\Data_block;
use App\Models\Parent_tags_reference;
use App\Models\Tag;
use App\Models\Tags_reference;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $tags;
    private $datablocks;
    private $reference;
    private $types;
    private $type_categories;
    public function index()
    {
        ini_set('memory_limit','512M');
        \Contour::getConfigManager()->turnOnErrorReporting();
        \Contour::getConfigManager()->setTimeLimit(60);
        $this->tags = [];
        $this->datablocks = [];
        $this->reference = [];
        $this->types = [];
        $this->type_categories = [];
        $start = microtime(true);
        $this->loadIntoMemoryChuncking();
        //$this->loadIntoMemoryChuncking();
//        $tagsReference = Tags_reference::all()->chunk(1000);
        $bytes = memory_get_usage();
        $kilobytes = $bytes / 1024;
        $megabytes = intval($kilobytes / 1024);
        echo "final memory usage: " . $megabytes . " MB<br />";
        echo "time spent: " . number_format (microtime(true) - $start, 3) . " Seconds<br />";

    }

    function loadIntoMemoryChuncking()
    {
        Tag::chunk(1000,function($rowTags) {
            $this->tags = array_merge($this->tags,$rowTags->all());
        });
        Data_block::chunk(1000, function($blocks) {
            $this->datablocks = array_merge($this->datablocks, $blocks->all());
        });
        Tags_reference::chunk(1000, function($blocks) {
            $this->reference = array_merge($this->reference, $blocks->all());
        });
    }

    function loadWithoutChunking()
    {

        $this->tags = \DB::select('select * from tags');
        $this->datablocks = \DB::select('select * from data_blocks');
        $this->reference = \DB::select('select * from tags_reference');
//        $this->types = \DB::select('select * from types');
//        $this->type_categories = \DB::select('select * from type_categories');
        $test = "";
//        $this->tags = Tag::all();
//        $this->datablocks = Data_block::all();
//        $this->reference = Tags_reference::all();
    }
    function loadWithEloquentAndNoChunking()
    {
        $this->tags = Tag::all();
        $this->datablocks = Data_block::all();
        $this->reference = Tags_reference::all();
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
}
