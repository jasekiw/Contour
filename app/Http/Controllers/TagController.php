<?php
namespace App\Http\Controllers;

use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;
use App\Http\Requests;
use Input;
use Response;
use stdClass;

/**
 * Class TagController
 * @package App\Http\Controllers
 */
class TagController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /tag
	 *
	 * @return Response
	 */
	public function index()
	{
		$topTags = DataTags::get_by_parent_id(-1);
		$view = \View::make('tags.index');
		$view->title = "Tag Browser";
		$view->tags = $topTags;
		return $view;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tag/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$answer = new stdClass();
		$answer->success = false;
		$name = Input::get("name");
		$parent_id = Input::get("parent_id");
		$type = Input::get("type");

		if($name !== null && $parent_id !== null && $type !== null)
		{
			$parent_id = intval($parent_id);
			if(Types::exists($type))
			{
				$type = Types::get_type_by_name($type, TypeCategory::getTagCategory());

				$tag = new DataTag($name, $parent_id, $type);
				$tag->create();
				$answer->id = $tag->get_id();
				$answer->name = $tag->get_name();
				$answer->success = true;
			}


		}

		return json_encode($answer);
	}

	public function getTypes()
	{
		$types = Types::get_all_tag_types();
		foreach($types as $type)
		{
			echo '<option value="' . $type->getName() . '" >' . $type->getName() . '</option>';
		}
		exit;
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tag
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /tag/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$tag = DataTags::get_by_id($id);
		$topTags = DataTags::get_by_parent_id($id);
		$view = \View::make('tags.index');
		$view->title = "Tag Browser";
		$view->subtitle = $tag->get_name();
		$view->tags = $topTags;
		$view->currently_viewing = $tag;
		return $view;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tag/{id}/edit
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
	 * PUT /tag/{id}
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
	 * DELETE /tag/delete
	 *
	 *
	 * @return Response
	 */
	public function destroy()
	{
		$answer = new stdClass();
		$answer->success = false; // default
		$id = Input::get("id");
		if($id !== null)
		{
			$id = intval($id);
			$tag = DataTags::get_by_id($id);
			if($tag !== null)
			{
				$tag->delete_recursive();
				$answer->success = true;
			}

		}
		//$answer->id = $id;
		return json_encode($answer);
	}

	/**
	 * Moves the tags to another tag
	 */
	public function move()
	{
		$answer = new stdClass;
		$sources = Input::get("source");

		$target = intval( Input::get("target") );
		if(intval($target) != -1)
		{
			$target = DataTags::get_by_id($target);
			if($target !== null)
			{
				$target = $target->get_id();
			}
		}

		if($target !== null)
		{
			foreach($sources as $source)
			{
				$current = DataTags::get_by_id(intval($source));
				if($current->get_id() != $target)
				{
					$current->set_parent_id($target);
				}

				$current->save();

			}
			$answer->success = true;
		}
		else
			$answer->success = false;
		return json_encode($answer);
	}

}