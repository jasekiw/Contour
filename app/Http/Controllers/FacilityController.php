<?php
namespace App\Http\Controllers;

use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\excel\templates\TableCompiler;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use app\libraries\theme\data\TableBuilder;
use \app\libraries\types\Types;
use Response;

/**
 * Class FacilityController
 */
class FacilityController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /facility
	 *
	 * @return Response+
	 */
	public function index($letter= null)
	{
		$view = \View::make("facilities.index");
		$facilities = DataTags::get_by_string("excel", -1)->findChild("facilities")->get_children();

		if(isset($letter))
		{
			$facilities = $facilities->getTagsStartingWith($letter);
			$view->current = $letter;
			$view->letter = $letter;
		}
		else
		{
			$view->current = "all";
			$facilities = $facilities->getAsArray(TagCollection::SORT_TYPE_ALPHABETICAL);
		}

		$view->title = 'Facilities';
		$view->facilities = $facilities;

		return $view;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /facility/create
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /facility
	 *
	 * @return Response
	 */
	public function store()
	{

	}

	/**
	 * Display the specified resource.
	 * GET /facility/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function experimentalShow($id)
	{
		$view = \View::make("facilities.experimentalEditor"); // gets the editor view
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
	 * Display the specified resource.
	 * GET /facility/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$view = \View::make("facilities.editor"); // gets the editor view
		$facility = DataTags::get_by_id($id); // gets the facility tag
		$view->title = $facility->get_name();
		$summary= $facility->get_children();
		$revenue = $summary->get("revenue")->get_children();
		$expenses = $summary->get("expenses")->get_children();

		$summary->remove("expenses");
		$summary->remove("revenue");

		$columns = $summary->getTagWithTypeAsArray(Types::get_type_column());
		$summary->remove(Types::get_type_column());
		$summaryBlocks = array();
		foreach($summary->getAsArray() as $tag)
		{
			foreach($columns as $column)
			{
				$datablock = DataBlocks::getByTagsArray(array($tag,$column ));
				array_push($summaryBlocks, $datablock);
			}
		}

		$expenseBlocks = array();
		foreach($expenses->getAsArray() as $tag)
		{
			foreach($columns as $column)
			{
				$datablock = DataBlocks::getByTagsArray(array($tag,$column ));
				array_push($expenseBlocks, $datablock);
			}
		}

		$revenueBlocks = array();
		foreach($revenue->getAsArray() as $tag)
		{
			foreach($columns as $column)
			{
				$datablock = DataBlocks::getByTagsArray(array($tag,$column ));
				array_push($revenueBlocks, $datablock);
			}
		}

		$view->summary = $summary;
		$view->summaryBlocks = $summaryBlocks;
		$view->columns = $columns;
		$view->revenue = $revenue;
		$view->revenueBlocks = $revenueBlocks;
		$view->expenses = $expenses;
		$view->expenseBlocks = $expenseBlocks;
		$view->summaryTable = new TableBuilder($summary->getAsArray(), $columns, $summaryBlocks, "summaryTable" );

		$view->revenueTable = new TableBuilder($revenue->getAsArray(), $columns, $revenueBlocks, "revenueTable" );
		$view->expensesTable = new TableBuilder($expenses->getAsArray(), $columns, $expenseBlocks, "expensesTable" );

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

	/**
	 * Update the specified resource in storage.
	 * PUT /facility/{id}
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
	 * DELETE /facility/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}