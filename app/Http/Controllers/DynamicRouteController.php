<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class DynamicRouteController
 * @package App\Http\Controllers
 */
class DynamicRouteController extends Controller {

	public function get($id)
	{
		\Contour::getRoutesManager()->callRouteGet($id);
	}

	public function post($id)
	{
		\Contour::getRoutesManager()->callRoutePost($id);
	}

}