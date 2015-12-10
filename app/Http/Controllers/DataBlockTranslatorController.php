<?php
namespace App\Http\Controllers;

use app\libraries\Data_Blocks\formula\Parser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class DataBlockTranslatorController
 */
class DataBlockTranslatorController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /datablocktranslator
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * POST /api/getValue
	 * json datablock => value
	 * @return string
     */
	public function getValue()
	{

		$value = \Input::get("datablock");
		$context = intval( ( \Input::get("context") != null ? \Input::get("context") : -1) );
		$parser = new Parser();

		$response = new \stdClass();
		$response->result = $parser->parse($value, $context);
		$response->success = true;
		return  json_encode($response);

	}


}