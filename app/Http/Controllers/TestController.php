<?php

namespace App\Http\Controllers;

use app\libraries\memory\MemoryDataManager;
use App\Models\Data_block;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class TestController
 * @package App\Http\Controllers
 */
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        ini_set('memory_limit','512M');
        \Contour::getConfigManager()->turnOnErrorReporting();
        \Contour::getConfigManager()->setTimeLimit(60);

        $start = microtime(true);
        MemoryDataManager::initialize();
        $manager = MemoryDataManager::getInstance();
        $manager = MemoryDataManager::getInstance();
        $manager = MemoryDataManager::getInstance();
        $manager = MemoryDataManager::getInstance();
        $manager = MemoryDataManager::getInstance();
        $manager = MemoryDataManager::getInstance();


        $tag = $manager->dataTagManager->get_by_id(1475);
        $tag2 = $manager->dataTagManager->get_by_id(1487);
        $tags = array($tag, $tag2);
        $block = $manager->dataBlockManager->getByTagsArray($tags);
        $stop = microtime(true);
        $bytes = memory_get_usage();
        $kilobytes = $bytes / 1024;
        $megabytes = intval($kilobytes / 1024);
        echo "final memory usage: " . $megabytes . " MB<br />";
        echo "time spent: " . number_format ($stop - $start, 3) . " Seconds<br />";
        echo xdebug_peak_memory_usage() / 1024/1024;
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
