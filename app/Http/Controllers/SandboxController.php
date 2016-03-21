<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use app\libraries\excel\formula\conversion\FormulaConversion;
use app\libraries\tags\DataTags;
use Route;
use Theme;

class SandboxController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /sandbox
	 *
	 * @return \Response
	 */
	public function index()
	{
//		ignore_user_abort(true);
//		$converter = new FormulaConversion();
//		//        $datablock->set_value($actualValue);
//		$sheet = DataTags::get_by_string("Excel", -1)->findChild("EG_-_PH_Corporate_Roll_UP");
//
//		$children = $sheet->get_children();
//		$columns = $children->getColumnsAsArray();
//		$rows = $children->getRowsAsArray();
//		$total = sizeOf($rows) * sizeOf($columns);
//		$count = 0;
//		ob_start();
//		foreach($rows as $row)
//		{
//			foreach($columns as $column)
//			{
//
//
//				$datablock = \app\libraries\datablocks\staticform\DataBlocks::getByTagsArray(array($column, $row ));
//				echo $datablock->getValue();
//				echo "<br />";
//				echo $converter->ConvertToTagFormat($datablock);
//				echo "<br />";
//				$count++;
//				UserMeta::save("conversionProgress",$count . "/" . $total );
//				UserMeta::save("aborted",connection_aborted() );
//				if(connection_aborted() === 1 )
//				{
//					exit;
//				}
//				flush();
//				ob_flush();
//
//
//
//
//			}
//
//
//		}

//		Theme::enqueue_script('datatagInterfacer', "assets/js/datablock_editor/DatatagInterfacer.js");
//		Theme::enqueue_script('dataBlock_interfacer', "assets/js/datablock_editor/DatablockInterfacer.js");
//		//Theme::enqueue_script('data_block_editor', "assets/js/datablock_editor/ts/main.js");
//		$view = \View::make("sandbox.index");
//		$view->title = "sandbox";


//		return $view;


       return \View::make('partials.listroutes');


	}

	/**
	 * Show the form for creating a new resource.
	 * GET /sandbox/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /sandbox
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /sandbox/{id}
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
	 * GET /sandbox/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /sandbox/{id}
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
	 * DELETE /sandbox/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}