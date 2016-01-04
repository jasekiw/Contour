<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_Access_Group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /user
	 *
	 * @return Response
	 */
	public function index()
	{
		$view = \View::make('user.index');
		$view->title = "Users";
		$users = User::all();
		$view->users = $users;
		return $view;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /user/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$view = \View::make('user.create');
		$groups = User_Access_Group::all();
		$view->title = "Create a new user";
		$view->groups = $groups;
		return $view;
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /user
	 *
	 * @return Response
	 */
	public function store()
	{
		$username = \Input::get("username");
		$password = \Input::get("password");
		$userAccessId = \Input::get("group");
		$email = \Input::get("email");
		$user = new User();
		$user->username = $username;
		$user->password = \Hash::make($password);
		$user->user_access_group_id = $userAccessId;
		$user->email = $email;
		$user->save();
		return redirect()->route("users_index")->with('message', "New user created!");
	}

	/**
	 * Display the specified resource.
	 * GET /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::where('id', '=',$id )->first();
		$view = \View::make('user.show');
		$view->title = "User - " . $user->username;
		$groups = User_Access_Group::all();
		$view->groups = $groups;
		$view->user = $user;
		return $view;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /user/{id}/edit
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
	 * PUT /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{	$userID = \Input::get("user");
		$user = User::where('id', '=', $userID)->first();
		$newPassword = \Input::get('password');
		if($newPassword !== null || strlen($newPassword) != 0)
			$user->password = \Hash::make(\Input::get('password'));
		$user->user_access_group_id = \Input::get('group');
		$user->save();
		return redirect()->route('users_show', [$userID])->with("message", "Successfully Saved");

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}