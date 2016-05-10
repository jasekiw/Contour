<?php
namespace App\Http\Controllers;

use app\libraries\contour\Contour;
use app\libraries\theme\data\LinkGenerator;
use app\libraries\theme\menu\item\MenuItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Response;
use Route;

/**
 * Class MenuController
 * @package App\Http\Controllers
 */
class MenuController extends Controller
{

    /**
     * @var MenuItem[]
     */
    private $menuItems;
    /**
     * @var MenuItem[]
     */
    private $menuItemsThatWillBeSaved;

    /**
     * Display a listing of the resource.
     * GET /menu
     *
     * @param null $letter
     * @return Response
     */
    public function index($letter = null)
    {

        $menus = Contour::getThemeManager()->getMenuManager()->getMenus();
        $stdMenus = [];
        foreach ($menus as $menu) {
            $stdMenu = new \stdClass();
            $stdMenu->name = $menu->getName();
            $stdMenu->id = $menu->get_id();
            $stdMenus[] = $stdMenu;
        }
        $view = \View::make('general.list');
        LinkGenerator::generateAlphabetLinks($view, 'menu_index_letter');
        LinkGenerator::setupLinksAtoZ($view, 'menu.edit', 'name', 'id', $letter, $stdMenus);
        $view->indexURL = route('menu.index');
        $view->title = "Menus";
        $view->newTitle = "Create New Menu";
        $view->newLink = route('menu.create');
        return $this->render($view);
    }

    /**
     * Show the form for creating a new resource.
     * POST /menu/create
     *
     * @return Response
     */
    public function create()
    {
        $view = \View::make("menu.create");
        $view->title = "Create new Menu";
        return $this->render($view);
    }

    /**
     * Store a newly created resource in storage.
     * POST /menu
     *
     * @return Response
     */
    public function store()
    {
        $response = new \stdClass();
        $name = \Input::get("name");
//		$view = \View::make("menu.index");
//		$view->title = "Menus";
        $menuManager = Contour::getThemeManager()->getMenuManager();
        if (!isset($name)) {
            $response->success = false;
//			$view->message = "no name given";
            return Redirect::route("menu.create")->with("message", "Name has to be filled out!");
        }
        $id = $menuManager->addMenu($name);
        return Redirect::route("menu.edit", [$id])->with("message", "Menu Created");
    }

    /**
     * Display the specified resource.
     * GET /menu/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * GET /menu/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        Contour::getThemeManager()->enqueueScript('jquery-ui', 'theme/js/jquery-ui/jquery-ui-1.10.4.custom.js');
//		Contour::getThemeManager()->enqueueScript('menu_editor','assets/ts/contour/menu_editor/menu_editor.js');
        $menu = Contour::getThemeManager()->getMenuManager()->get_menu_by_id($id);
        if (isset($menu)) {
            /** @var \Illuminate\Routing\Route[] $routes */
            $routes = Route::getRoutes()->getRoutes();
            $view = \View::make("menu.edit");
            $view->title = "Edit Menu";
            $view->menuItems = $menu->getMenuItems();
            $view->menu = $menu;
            $view->routes = $routes;
            return $this->render($view);
        }
        return \View::make('errors.404');
    }

    /**
     * Update the specified resource in storage.
     * PUT /menu/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $links = \Input::get("links");
        $menu = Contour::getThemeManager()->getMenuManager()->get_menu_by_id($id);
        $this->menuItems = $menu->getMenuItems();
        foreach ($links as $link) {
            $menuItem = $this->getLinkByName($link['name']);
            if (isset($menuItem)) {
                $menuItem->set_href($link['link']);
                $menuItem->set_sort_number($link['order']);
                $menuItem->set_icon($link['icon']);
                $menuItem->save();
                continue;
            }
            if ($link['name'] != "")
                $menu->addItem($link['name'], $link['link'], $link['order'], $link['icon']);
        }
        foreach ($this->menuItems as $item)
            if (!isset($this->menuItemsThatWillBeSaved[$item->getName()]))
                $item->delete();
        $isAjax = (boolean)\Input::get("isAjax");
        if (!$isAjax)
            return Redirect::route("menu.index")->with("message", "Menu Saved");

        $resonse = new \stdClass();
        $resonse->redirect = route("menu.index");
        $resonse->message = "Menu Saved";
        $resonse->success = true;
        \Session::flash('message', 'Menu Saved');
        return json_encode($resonse);
    }

    /**
     * @param $name
     * @return MenuItem | null
     */
    private function getLinkByName($name)
    {
        foreach ($this->menuItems as $item)
            if ($item->getName() == $name) {
                $this->menuItemsThatWillBeSaved[$item->getName()] = $item;
                return $item;
            }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /menu/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $menu = Contour::getThemeManager()->getMenuManager()->get_menu_by_id($id);
        $menu->delete();


        return Redirect::route("menu.index")->with("message", "Menu Deleted");
    }

}