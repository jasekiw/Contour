<?php

namespace App\Http\Controllers;

use app\libraries\theme\data\LinkGenerator;
use App\Models\User_Access_Group;
use app\libraries\contour\Contour;
use Illuminate\Http\Request;

use App\Http\Requests;

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
    public function index($letter = null)
    {

        $view = \View::make('general.list');
        LinkGenerator::generateAlphabetLinks($view, 'user_access_groups_index_letter');
        LinkGenerator::setupLinksAtoZ($view, 'user_access_groups_show', 'name', 'id', $letter, User_Access_Group::all());
        /** @noinspection PhpUndefinedFieldInspection */
        $view->indexURL = route('user_access_groups_index');
        /** @noinspection PhpUndefinedFieldInspection */
        $view->title = "User Access Groups";
        /** @noinspection PhpUndefinedFieldInspection */
        $view->newTitle = "Create New User Access Group";
        /** @noinspection PhpUndefinedFieldInspection */
        $view->newLink = route('user_access_groups_create');
        return $this->render($view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menues = Contour::getThemeManager()->getMenuManager()->getMenus();
        $menuIds = [];
        foreach ($menues as $menu)
            $menuIds[$menu->get_id()] = $menu->getName();

        $view = \View::make("user_access_groups.create");
        $view->title = "Create new user access Group";
        $view->menus = $menuIds;
        return $this->render($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $groupName = \Input::get("name");
        $group = new User_Access_Group();
        $group->name = $groupName;
        $group->save();
        /** @var \Illuminate\Routing\Redirector $redirect */
        $redirect = redirect();
        return $redirect->route("user_access_groups_index")->with("message", "New User Access Group created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menues = Contour::getThemeManager()->getMenuManager()->getMenus();
        $menuIds = [];
        foreach ($menues as $menu)
            $menuIds[$menu->get_id()] = $menu->getName();
        $group = User_Access_Group::where('id', '=', $id)->first();
        $view = \View::make('user_access_groups.show');
        $view->title = "User Access Group " . $group->name;
        $view->group = $group;
        $view->menus = $menuIds;
        return $this->render($view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $groupId = \Input::get("group");
        $group = User_Access_Group::where('id', '=', $groupId)->first();
        $name = \Input::get("name");
        if ($name != null && strlen($name) != 0)
            $group->name = $name;
        $group->menu_id = \Input::get("menu");
        $group->save();
        /** @var \Illuminate\Routing\Redirector $redirect */
        $redirect = redirect();
        return $redirect->route('user_access_groups_index')->with('message', "Successfully Saved!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
