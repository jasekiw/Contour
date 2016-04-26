<?php
namespace App\Http\Controllers;

use app\libraries\tags\DataTags;
use app\libraries\theme\menu\item\MenuItem;
use app\libraries\contour\Contour;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Input;
use Response;
use Route;

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
		$request = Request::create( route('get_menu', ['id' => $menuID]), 'GET');
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
		$view = \View::make('menuitem.edit');
		$tag = DataTags::get_by_id($id);
		$menuItem = new MenuItem($tag);
		$view->title = "Editing: " . $menuItem->getName();
		$view->menuItem = $menuItem;
		return $this->render($view);
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
		$tag = DataTags::get_by_id($id);
		$menuItem = new MenuItem($tag);
		$name = \Input::get('name');
		$link = \Input::get('link');
		$icon = \Input::get('icon');
		$menuItem->set_href($link);
		$menuItem->set_icon($icon);
		$menuItem->set_name($name);
		$menuID = $menuItem->getMenuID();
		$request = Request::create( route('get_menu', ['id' => $menuID]), 'GET');
		return Route::dispatch($request)->getContent();

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