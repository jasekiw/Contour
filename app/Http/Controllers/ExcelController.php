<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use app\libraries\excel\templates\imports\Importer;
use \app\libraries\excel\templates\imports\Datablocks\DataBlockImporter;

/**
 * Class ExcelController
 */
class ExcelController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /excel
	 *
	 * @return Response
	 */
	public function index()
	{

		//return \View::make("Excel.index")->with("reader", $results);

	}

	function turn_on_error_reporting()
	{
		ini_set('max_execution_time', 0);
		set_time_limit(0);
		ini_set('display_errors',1);
		error_reporting(E_ALL);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /excel/convert
	 *
	 * @return Response
	 */
	function convert()
	{
		$this->turn_on_error_reporting();
		$datablock_importer = new DataBlockImporter();
		$datablock_importer->runImport();

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /excel/create
	 *
	 * @return Response
	 */
	public function create()
	{
//		$classtag = new DataTag("EG Corporate Projections", null, "class", 0);
//		$categoryTag = new DataTag("category_name", null, "category", 25);
//		$instance_tag = newDataTag("instance_name", null, "instance", 10);
//		$classtag1 = new DataTag("Ridgewood", null, "class", 0);
//		$categoryTag1 = new DataTag("category_name1", null, "category", 26);
//		$instance_tag1 = newDataTag("instance_name1", null, "instance", 11);
//		$tagcollection = new \app\libraries\tags\collection\TagCollection($classtag,$categoryTag, $instance_tag);
//		$tagcollection1 = new \app\libraries\tags\collection\TagCollection($classtag1,$categoryTag1, $instance_tag1);
//		$all_cells = array(array("tags" => $tagcollection), array("tags" => $tagcollection1));
//
//		$current_cell = array("value" => "=Ridgewood!J25:Ridgewood!K26", "tags" => $tagcollection);
//		$converter = new FormulaConversion();
//		$result = $converter->ConvertToTagFormat($current_cell, $all_cells);
//		echo $result;
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /excel
	 *
	 * @return Response
	 */
	public function store()
	{
		//

		//require storage_path() . '/kint/Kint.class.php';
		$this->turn_on_error_reporting();
		$importer = new Importer();
		$importer->run();

	}

	/**
	 * Display the specified resource.
	 * GET /excel/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /excel/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$datatag = \app\libraries\tags\DataTags::get_by_id($id);
		$view = \View::make('excel.index');
		$view->title = "Excel Editor";
		$view->subtitle = $datatag->get_name();
		$parent_id = $datatag->get_parent_id();
		$url = null;
		if($parent_id != -1)
		{
			$url = URL::action('TagController@show', [$parent_id]);
		}
		else
		{
			$url = route('index_tags');
		}
		$view->backtoLink = '<a href="' . $url . '"><i class="fa fa-arrow-left back_arrow"></i>Back to Tag Browser</a>';
		$view->sheet = $datatag;
		return $view;
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /excel/{id}
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
	 * DELETE /excel/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}