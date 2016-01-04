<?php

namespace App\Http\Controllers;

use App\Models\User_Access_Group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class UserAccessGroupsController
 * @package App\Http\Controllers
 */
class UserAccessGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = User_Access_Group::all();
        $view = \View::make('user_access_groups.index');
        $view->title = "User Access Groups";
        $view->groups = $groups;
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = \View::make("user_access_groups.create");
        $view->title = "Create new user access Group";
        return $view;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $groupName = \Input::get("group");
        $group = new User_Access_Group();
        $group->name = $groupName;
        $group->save();
        return redirect()->route("user_access_groups_index")->with("message", "New User Access Group created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = User_Access_Group::where('id', '=', $id)->first();
        $view = \View::make('user_access_groups.show');
        $view->title = "User Access Group " . $group->name;
        $view->group = $group;
        return $view;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $groupId = \Input::get("group");
        $group = User_Access_Group::where('id', '=', $groupId)->first();
        $name = \Input::get("name");
        if($name != null && strlen($name) != 0)
            $group->name = $name;
        $group->save();
        return redirect()->route('user_access_groups_show', [$groupId])->with('message', "Successfully Saved!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
