<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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