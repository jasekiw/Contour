<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class MenuItemController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /menuitem
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /menuitem/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /menuitem
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = Input::get("name");
		$link = Input::get("link");
		$icon = Input::get("icon");
		$menuID = Input::get("menu");
		Contour::getThemeManager()->getMenuManager()->get_menu_by_id($menuID)->addItem($name,$link, 0, $icon);
		$request = Request::create('/menu', 'GET', array($menuID));
		return Route::dispatch($request)->getContent();

	}

	/**
	 * Display the specified resource.
	 * GET /menuitem/{id}
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
	 * GET /menuitem/{id}/edit
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
	 * PUT /menuitem/{id}
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
	 * DELETE /menuitem/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}