<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/18/2015
 * Time: 11:29 AM
 */

namespace app\libraries\theme\menu;

use app\libraries\theme\menu\item\collection\MenuItemCollection;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\theme\menu\item\MenuItem;
use app\libraries\theme\system\System;
use app\libraries\types\Types;
use App\Models\User_Access_Group;

/**
 * Class MenuManager
 * @package app\libraries\theme\menu
 */
class MenuManager
{
    
    private static $cached_menus = null;
    private $main_menu_tag = null;
    /** @var Menu[] $menus   */
    private $menus;
    
    /**
     * MenuManager constructor.
     */
    public function __construct()
    {
        $this->menus = [];
        $this->initializeMenus();
    }
    
    private function initializeMenus()
    {
        $menus = self::getMenusTag()->get_children()->getAsArray();
        foreach ($menus as $key => $menuTag) {
            array_push($this->menus, new Menu($menuTag));
        }
    }
    
    public static function getMenusTag()
    {
        if (isset(self::$cached_menus)) {
            return self::$cached_menus;
        }
        $system = System::get_system_tag();
        $menus = $system->findChild('menus');
        if (isset($menus)) {
            self::$cached_menus = $menus;
            return $menus;
        } else {
            $menus = new DataTag("menus", $system->get_id(), Types::get_type_folder(), 1);
            $menus->create();
            self::$cached_menus = $menus;
            return $menus;
        }
    }
    
    /**
     * Gets the Menus
     * @return Menu[]
     */
    public function getMenus()
    {
        return $this->menus;
    }
    
    /**
     * @param $name
     *
     * @return Menu
     */
    public function get_menu($name)
    {
        foreach ($this->menus as $menu) {
            if ($menu->getName() == $name) {
                return $menu;
            }
        }
        return null;
    }
    
    public function getAssociatedMenu()
    {
        /** @var \App\Models\User $user */
        $user = \Auth::user();
        $groupID = $user->user_access_group_id;
        $group = User_Access_Group::where('id', '=', $groupID)->first();
        $menuId = $group->menu_id;
        if ($menuId == null)
            return null;
        return $this->get_menu_by_id($menuId);
    }
    
    /**
     * Gets a menu by the id
     *
     * @param $id
     *
     * @return Menu
     */
    public function get_menu_by_id($id)
    {
        foreach ($this->menus as $menu) {
            if ($menu->get_id() == $id) {
                return $menu;
            }
        }
        return null;
    }
    
    /**
     * Adds a new Menu By Name
     *
     * @param $name
     *
     * @return int The Menu ID
     */
    public function addMenu($name)
    {
        $menu = Menu::make($name, self::getMenusTag());
        array_push($this->menus, $menu);
        return $menu->get_id();
    }
    
}