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
    /**
     * @var string
     */
    private $name;
    /**
     * @var DataTag
     */
    private $menuTag;
    /**
     * @var DataTag
     */
    private $itemsTag;
    /*
     * @var MenuItem[]
     */
    private $items;

    /**
     * Creates a new menu and adds the database items
     * @param string $name
     * @param DataTag $menusTag
     * @return Menu
     */
    public static function make($name, $menusTag)
    {

        $menuTag = new DataTag($name, $menusTag->get_id(), Types::get_type_by_name("folder", TypeCategory::getTagCategory()) );
        $menuTag->create();
        $items = new DataTag("items", $menuTag->get_id(), Types::get_type_by_name("folder", TypeCategory::getTagCategory()) );
        $items->create();
        return $menu = new Menu($menuTag);
    }


    /**
     * Sets a menu up from the database items
     * Menu constructor.
     * @param DataTag $menuTag
     */
    function __construct($menuTag, $itemsTag = null)
    {
        $this->name = $menuTag->get_name();
        $this->menuTag = $menuTag;
        if(isset($itemsTag))
            $this->itemsTag = $itemsTag;
        else
        $this->itemsTag = DataTags::get_by_string("items",$menuTag->get_id() );

        $this->items = $this->itemsTag->get_children()->getAsArray();

        foreach($this->items as $key => $value) // converting tags to menu items
        {
            $this->items[$key] = new Menuitem($value);
        }
    }


    /**
     * Gets the menu items
     * @return Menuitem[]
     */
    public function getMenuItems()
    {
        return $this->items;
    }

    public function addItem($name, $url, $sort, $icon = null)
    {

        $menuItem = MenuItem::make($this->itemsTag, $name, $url, $sort,  $icon);
        array_push($this->items,$menuItem );
        return $menuItem;
    }

    /**
     * Returns the name of the menu
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    public function get_id()
    {
        return $this->menuTag->get_id();
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