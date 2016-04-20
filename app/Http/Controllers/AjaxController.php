<?php
namespace App\Http\Controllers;


use App\Http\Requests;
use Response;
use app\libraries\theme\Theme;

/**
 * Class AjaxController
 * @package App\Http\Controllers
 */
class AjaxController extends Controller {




	/**
	 * Display the specified resource.
	 * GET /ajax/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function get($id)
	{
		$parameters = explode("/",$id);
		$id = $parameters[0];
		array_shift($parameters);
		Theme::get_ajax_manager()->call_script_get($id, $parameters );
	}


	/**
	 * Update the specified resource in storage.
	 * POST /ajax/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function post($id)
	{
		$parameters = explode("/",$id);
		$id = $parameters[0];
		array_shift($parameters);
		Theme::get_ajax_manager()->call_script_post($id, $parameters );
	}




}