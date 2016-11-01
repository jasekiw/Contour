<?php

namespace App\Http\Controllers;

use app\libraries\theme\data\LinkGenerator;
use App\Models\User;
use App\Models\User_Access_Group;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Response;
use Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /user
     *
     * @return Response
     */
    public function index($letter = null)
    {
        $letter = strtoupper($letter);
        $view = \View::make('general.list');
        $view->title = "Users";
        $view->newLink = route('users_create');
        $view->newTitle = "create new user";
        LinkGenerator::generateAlphabetLinks($view, 'users_index_letter');
        LinkGenerator::setupLinksAtoZ($view, 'users_show', 'username', 'id', $letter, User::all());
        $view->indexURL = route('users_index');
        return $this->render($view);
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
        return $this->render($view);

    }

    /**
     * Store a newly created resource in storage.
     * POST /user
     *
     * @return Response
     */
    public function store()
    {
        $username = Input::get("username");
        $password = Input::get("password");
        $userAccessId = Input::get("group");
        $email = Input::get("email");
        $user = new User();
        $user->username = $username;
        $user->password = \Hash::make($password);
        $user->user_access_group_id = $userAccessId;
        $user->email = $email;
        $user->save();

        return Redirect::route("users_index")->with('message', "New user created!");
    }

    /**
     * Display the specified resource.
     * GET /user/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::where('id', '=', $id)->first();
        $view = \View::make('user.edit');
        $view->title = "User - " . $user->username;
        $groups = User_Access_Group::all();
        $view->groups = $groups;
        $view->user = $user;
        return $this->render($view);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /user/{id}/edit
     *
     * @param  int $id
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
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $user = User::where('id', '=', $id)->first();
        $email = Input::get('email', '');
        $username = Input::get('username',  "");
        $newPassword = Input::get('password');
        if ($newPassword !== null || strlen($newPassword) != 0)
            $user->password = \Hash::make($newPassword);
        $user->user_access_group_id = Input::get('group');
        if(strlen($username) > 2)
            $user->username = $username;
        if(strlen($email) > 2)
            $user->email = $email;
        $user->save();
        return Redirect::route('users_show', [$id])->with("message", "Successfully Saved");
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}