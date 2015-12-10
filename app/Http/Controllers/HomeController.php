<?php
namespace App\Http\Controllers;

use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\types\Types;
use App\Models\Revision;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use app\libraries\tags\DataTag;
use \app\libraries\tags\DataTags;
use app\libraries\user\UserMeta;

class HomeController extends Controller {


	private $reports = array();
	private $facilities = array();
	private $reportsDisplayed = 0;
	private $facilitiesDisplayed = 0;
	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	/**
	 * @return \Illuminate\View\View
     */
	public function index()
	{


		$view = \View::make('dashboard.view');
		$view->title = "Dashboard";
		$first_name = UserMeta::get("first_name");
		if(strlen($first_name) > 0)
		{
			$view->first_name = $first_name;
		}

		$rows = Revision::limit(30)->groupBy("revisionable_id")->get();
		$type = Types::get_type_sheet();
		/** @var Revision $row */


		foreach($rows as $row)
		{


			if($this->reportsDisplayed >= 4 && $this->facilitiesDisplayed >= 4) // no more than 4 on each side
			{
				break;
			}
			if($row->revisionable_type == "Tag")
			{

				$tag = DataTags::get_by_id($row->revisionable_id);
				if($tag->get_type()->get_id() == $type->get_id())
					$sheet = $tag;
				else
					$sheet = $tag->get_a_parent_of_type($type);

				$date = new \DateTime($tag->updated_at());
				$this->addSheet($sheet, $date );
			}
			else if($row->revisionable_type == "Data_block")
			{
				$block = DataBlocks::getByID($row->revisionable_id);
				$tags = $block->getTags();
				$tagsArray = $tags->getAsArray();
				if(sizeof($tagsArray) > 0)
				$tag = $tagsArray[0];
				else
					continue;
				$sheet = $tag->get_a_parent_of_type($type);
				$date = new \DateTime($block->updated_at());
				$this->addSheet($sheet, $date );

			}

		}

		$view->recentReports = $this->reports;
		$view->recentFacilities = $this->facilities;


		return $view;
	}

	/**
	 * @param DataTag $sheet
	 * @param \DateTime $date
	 */
	private function addSheet($sheet, $date)
	{
		if(isset($sheet) )
		{
			$parent = $sheet->get_parent();
			$sheet_array = array();
			$sheet_array["name"] =  $sheet->get_name();
			$sheet_array["updated"] =  $date->format("m-d-Y @ h:i A");
			$sheet_array["link"] =  \URL::action('ExcelController@edit', [$sheet->get_id()]);
			if($parent != null && strtoupper($parent->get_name()) === "FACILITIES")
			{
				if($this->facilitiesDisplayed >= 4)
					return;
				$this->facilitiesDisplayed++;
				$sheet_array["link"] =  \URL::action('FacilityController@show', [$sheet->get_id()]);
				array_push($this->facilities, $sheet_array);
			}
			else
			{
				if($this->reportsDisplayed >= 4)
					return;
				$this->reportsDisplayed++;
				$sheet_array["link"] =  \URL::action('ExcelController@edit', [$sheet->get_id()]);
				array_push($this->reports, $sheet_array);
			}
		}
	}

}
