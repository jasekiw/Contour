<?php
namespace App\Http\Controllers;

use app\libraries\tags\DataTags;
use App\Http\Requests;
use Response;

/**
 * Class ReportsController
 * @package App\Http\Controllers
 */
class ReportsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /reports
	 *
	 * @return Response
	 */
	public function index()
	{
		$view = \View::make("reports.index");
		$reports = DataTags::get_by_string("excel", -1)->findChild("reports")->get_children();
		if(isset($letter))
		{
			$reports = $reports->getTagsStartingWith($letter);
			$view->current = $letter;
			$view->letter = $letter;
		}
		else
		{
			$view->current = "all";
			$reports = $reports->getAsArray();
		}
		$view->title = 'Reports';
		$view->reports = $reports;
		return $view;
	}


}