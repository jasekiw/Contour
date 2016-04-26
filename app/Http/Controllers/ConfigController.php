<?php
namespace App\Http\Controllers;

use app\libraries\modelHelpers\ConfigHelper;
use App\Http\Requests;
use Input;
use Redirect;
use Response;

/**
 * Class ConfigController
 * @package App\Http\Controllers
 */
class ConfigController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /config
	 *
	 * @return Response
	 */
	public function show()
	{

		$view = \View::make('config.config');
		$view->title = "Configuration";

		return $this->render($view);
	}



	/**
	 * Store a newly created resource in storage.
	 * POST /config
	 *
	 * @return Response
	 */
	public function save()
	{
		$values = array('company_name', 'website_name', 'website_description');
		foreach ($values as $value)
		{
			ConfigHelper::save($value, Input::get($value));
		}

		ConfigHelper::save_file('favicon');
		ConfigHelper::save_file('logo');

		return Redirect::route('view_config')->with('message_title' , 'Successful!')->with('message' , 'Successfully updated the config!');
	}



}