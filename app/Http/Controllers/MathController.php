<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Response;

class MathController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /math
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $view = \View::make('math.index');
        $view->title = "Evaluator";
        $view->equation = Input::get('equation');
        $view->variables = Input::get('variables');
        $view->answer = Input::get('answer');
        return $view;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /math/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//


	}

	/**
	 * Store a newly created resource in storage.
	 * POST /math
	 *
	 * @return Response
	 */
	public function store()
	{
		//
        $view = \View::make('math.index');
        $view->title = "Evaluator";
        $view->equation = Input::get('equation');
        $view->variables = Input::get('variables');
        $evaluator = new app\libraries\Math\EvalMath();
        $variables = Input::get('variables');
        $variables = explode(";", $variables);
        foreach($variables as $variable)
        {
            $evaluator->evaluate($variable);
        }


        $answer = $evaluator->e(Input::get('equation'));
        $view->answer = $answer;

        return $view;
	}

	/**
	 * Display the specified resource.
	 * GET /math/{id}
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
	 * GET /math/{id}/edit
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
	 * PUT /math/{id}
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
	 * DELETE /math/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}