<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class MenuController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /menu
	 *
	 * @return Response
	 */
	public function index()
	{
		$view = \View::make("menu.index");
		$view->title = "Menus";
		return $view;
	}

	/**
	 * Show the form for creating a new resource.
	 * POST /menu/create
	 *
	 * @return Response
	 */
	public function create()
	{


	}

	/**
	 * Store a newly created resource in storage.
	 * POST /menu
	 *
	 * @return Response
	 */
	public function store()
	{
		$response = new \stdClass();
		$name = \Input::get("name");
		$view = \View::make("menu.index");
		$view->title = "Menus";
		if(!isset($name))
		{
			$response->success = false;
			$view->message = "no name given";
		}
		else
		{
			\Contour::getThemeManager()->getMenuManager()->addMenu($name);
		}
		return $view;
	}

	/**
	 * Display the specified resource.
	 * GET /menu/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$manager = \Contour::getThemeManager()->getMenuManager();
		$menu = \Contour::getThemeManager()->getMenuManager()->get_menu_by_id($id);
		if(isset($menu))
		{
			$view = \View::make("menu.edit");
			$view->title = "Edit Menu";
			$view->menuItems = $menu->getMenuItems();
			$view->menu = $menu;
			return $view;
		}


	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /menu/{id}/edit
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
	 * PUT /menu/{id}
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
	 * DELETE /menu/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}

}