<?php
namespace App\Http\Controllers;

use app\libraries\tags\DataTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

/**
 * Class AjaxTagController
 * @package App\Http\Controllers
 */
class AjaxTagController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /ajaxtag
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /ajaxtag/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /ajaxtag
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Gets the children tags for the given tag
     * @param $id
     * @return string
     */
    public function get_children($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if (isset($parent)) {
            $children = $parent->get_children()->getAsArray();

            foreach ($children as $index => $child) {
                $children[$index] = array(
                    "id" => $child->get_id(),
                    "name" => $child->get_name(),
                    "sort_number" => $child->get_sort_number(),
                    "type" => $child->get_type()->getName()
                );
            }
            $answer->tags = $children;

            $answer->success = true;

            return json_encode($answer);
        }
    }

    public function get_children_recursive($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if (isset($parent)) {
            $children = $parent->get_children_recursive()->getAsArray();

            foreach ($children as $index => $child) {
                $children[$index] = array(
                    "id" => $child->get_id(),
                    "name" => $child->get_name(),
                    "sort_number" => $child->get_sort_number(),
                    "type" => $child->get_type()->getName()
                );
            }
            $answer->tags = $children;

            $answer->success = true;

            return json_encode($answer);
        }
    }

    /**
     * Display the specified resource.
     * GET /ajaxtag/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /ajaxtag/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /ajaxtag/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /ajaxtag/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}