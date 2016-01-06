<?php
namespace App\Http\Controllers;

use app\libraries\excel\templates\TableCompiler;
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

	/**
	 * Display the specified resource.
	 * GET /facility/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$view = \View::make("excel.view"); // gets the editor view
		$compiler = new TableCompiler($id);
		$view->title = $compiler->name;
		$view->summary = $compiler->summary;
		$view->summaryBlocks = $compiler->summaryBlocks;
		$view->columns = $compiler->columns;
		$view->compositTags = $compiler->compositTags;
		$view->summaryTable = $compiler->summaryTable;
		$view->compositTables = $compiler->compositTables;
		return $view;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /facility/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$view = \View::make("excel.editor"); // gets the editor view
		$compiler = new TableCompiler($id);
		$view->title = $compiler->name;
		$view->summary = $compiler->summary;
		$view->summaryBlocks = $compiler->summaryBlocks;
		$view->columns = $compiler->columns;
		$view->compositTags = $compiler->compositTags;
		$view->summaryTable = $compiler->summaryTable;
		$view->compositTables = $compiler->compositTables;
		return $view;
	}


}