<?php

namespace App\Http\Controllers\api;

use app\libraries\tags\DataTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class ApiTagController
 * @package App\Http\Controllers\api
 */
class ApiTagController extends Controller
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
     * Gets the parent trace of a datablock
     * @return \Illuminate\Http\Response
     */
    public function getParentTrace()
    {
        $id = \Input::get("tag");
        $thetag = DataTags::get_by_id($id);
        $array = $thetag->getParentTrace();
        foreach($array as $index => $tag)
        {
            $array[$index] = $tag->get_name();
        }
        return json_encode($array);
    }


    /**
     * GET /api/tags/get_children/id
     * Gets the children tags for the given tag
     * @param $id
     * @return string
     */
    public function get_children($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if(!isset($parent)) {
            $answer->success = false;
            return json_encode($answer);
        }

        $children = $parent->get_children()->getAsArray();
        foreach($children as $index => $child)
        {
            $children[$index] = array( "id" => $child->get_id(),
                "name" =>  $child->get_name(),
                "sort_number" => $child->get_sort_number(),
                "type" => $child->get_type()->getName() );
        }
        $answer->tags = $children;
        $answer->success = true;
        return json_encode($answer);

    }


    /**
     * GET /api/tags/get_children_recursive/id
     * @param $id
     * @return string
     */
    public function get_children_recursive($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if(!isset($parent)) {
            $answer->success = false;
            return json_encode($answer);
        }
        $children = $parent->get_children_recursive()->getAsArray();
        foreach($children as $index => $child)
        {
            $children[$index] = array(
                "id" => $child->get_id(),
                "name" =>  $child->get_name(),
                "sort_number" => $child->get_sort_number(),
                "type" => $child->get_type()->getName() );
        }
        $answer->tags = $children;
        $answer->success = true;
        return json_encode($answer);

    }
}
