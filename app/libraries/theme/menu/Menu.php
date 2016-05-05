<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/3/2015
 * Time: 10:35 AM
 */

namespace app\libraries\theme\menu;

use app\libraries\database\DatabaseObject;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\theme\menu\item\MenuItem;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;

/**
 * Class Menu
 * @package app\libraries\theme\menu
 */
class Menu extends DatabaseObject
{
    
    /** @var string   */
    private $name;
    /** @var DataTag   */
    private $menuTag;
    
    /** @var MenuItem[]   */
    private $items;
    
    /**
     * Sets a menu up from the database items
     * Menu constructor.
     *
     * @param DataTag $menuTag
     */
    function __construct($menuTag)
    {
        $this->name = $menuTag->get_name();
        $this->menuTag = $menuTag;
        $this->items = $this->menuTag->get_children()->getAsArray();
        foreach ($this->items as $key => $value) // converting tags to menu items
            $this->items[$key] = new Menuitem($value);
    }
    
    /**
     * Creates a new menu and adds the database items
     *
     * @param string  $name
     * @param DataTag $menusTag
     *
     * @return Menu
     */
    public static function make($name, $menusTag)
    {
        
        $menuTag = new DataTag($name, $menusTag->get_id(), Types::get_type_by_name("folder", TypeCategory::getTagCategory()));
        $menuTag->create();
        $menu = new Menu($menuTag);
        return $menu;
    }
    
    public function addItem($name, $url, $sort, $icon = null)
    {
        $menuItem = MenuItem::make($this->menuTag, $name, $url, $sort, $icon);
        array_push($this->items, $menuItem);
        return $menuItem;
    }
    
    public function delete()
    {
        foreach ($this->getMenuItems() as $menutItem)
            $menutItem->delete();
        $this->menuTag->delete();
    }
    
    /**
     * Gets the menu items
     * @return Menuitem[]
     */
    public function getMenuItems()
    {
        usort($this->items, function ($a, $b) {
            /**
             * @var MenuItem $a
             * @var MenuItem $b
             */
            return $a->get_sort_number() - $b->get_sort_number();
        });
        return $this->items;
    }
    
    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    public function toStdClass()
    {
        $std = new \stdClass();
        $std->id = $this->get_id();
        $std->name = $this->getName();
        $std->updated_at = $this->updated_at();
        $std->created_at = $this->created_at();
        return $std;
    }
    
    public function get_id()
    {
        return $this->menuTag->get_id();
    }
    
    /**
     * Returns the name of the menu
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Gets the date at when the object was updated.
     * @return string
     */
    public function updated_at()
    {
        return $this->menuTag->updated_at();
    }
    
    /**
     * Gets the date at when the object was created
     * @return string
     */
    public function created_at()
    {
        return $this->menuTag->created_at();
    }
}