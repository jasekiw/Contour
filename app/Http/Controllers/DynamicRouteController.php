<?php
namespace App\Http\Controllers;

use app\libraries\contour\Contour;
use App\Http\Requests;

/**
 * Class DynamicRouteController
 * @package App\Http\Controllers
 */
class DynamicRouteController extends Controller {

	public function get($id)
	{
		Contour::getRoutesManager()->callRouteGet($id);
	}

	public function post($id)
	{
		Contour::getRoutesManager()->callRoutePost($id);
	}

}