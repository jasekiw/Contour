<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxDataBlockController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /ajaxdatablock
	 *
	 * @return Response
	 */
	public function index()
	{
		$view = \View::make("sandbox.ajaxblocks");
		$view->title = "ajaxblocks";
		return $view;


	}

	/**
	 * Show the form for creating a new resource.
	 * GET /ajaxdatablock/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /ajaxdatablock
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 *Post tag ids to get all associates datablocks
     */
	public function get_multiple_by_tags()
	{

		$tagIds = Input::get("tags");
		foreach($tagIds as $id)
		{
			//TODO: add logic
		}
		var_dump($tagIds);

	}

	/**
	 * Display the specified resource.
	 * GET /ajaxdatablock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /ajaxdatablock/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /ajaxdatablock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /ajaxdatablock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}