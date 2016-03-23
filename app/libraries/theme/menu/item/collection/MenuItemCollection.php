<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/18/2015
 * Time: 4:03 PM
 */

namespace app\libraries\theme\menu\item\collection;


use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\theme\menu\item\MenuItem;
use app\libraries\theme\menu\MenuManager;
use app\libraries\types\Types;

class MenuItemCollection
{
    /**
     * @var MenuItem[] array
     */
    private $array = array();
    private $options = array();
    /**
     * @var DataTag $itemsTag
     */
    private $itemsTag = null;
    /**
     * @var DataTag $menu_tag
     */
    private $menu_tag = null;


    /**
     * @param String $name
     */
    public function __construct($name = null)
    {

        if(isset($name))
        {
            $tag= new DataTag($name, MenuManager::getMenusTag()->get_id(), Types::get_type_folder(), 1);
            $$tag->create();
            $this->menu_tag = $tag;

            $this->itemsTag = new DataTag("menu_items",$tag->get_id(), Types::get_type_folder(), 1);
            $this->itemsTag->create();

            $this->option = new DataTag("options", $tag->get_id(), Types::get_type_folder(), 2);
            $this->option->create();
            $this->options = $tag->findChild("options")->get_children()->getAsArray();
        }



    }

    /**
     * @param DataTag $tag
     * @return MenuItemCollection
     */
    public static function get_by_tag($tag)
    {
        $menucollection = new MenuItemCollection();

        $menucollection->set_tag($tag);
        $menu_items =  $tag->findChild("menu_items");
        $menucollection->set_items_tag($menu_items);
        $menucollection->set_options_tag($tag->findChild("options"));
        $menuItems = $menu_items->get_children();
        foreach($menuItems->getAsArray() as $menu_item)
        {
            $menuItem = MenuItem::get_by_menu_tag($menu_item);
            $menucollection->add($menuItem);
        }
        return $menucollection;
    }

    /**
     * @param DataTag $tag
     */
    public function set_tag($tag)
    {
        $this->menu_tag = $tag;
    }

    public function set_items_tag($tag)
    {
        $this->itemsTag = $tag;
    }

    /**
     * @param DataTag $tag
     */
    public function set_options_tag($tag)
    {
        $this->options = $tag;
    }

    /**
     * @param MenuItem $menuItem
     */
    public function add($menuItem)
    {
        $sort_number = $menuItem->get_sort_number();
        if(isset($sort_number))
        {
            if(isset($this->array[$menuItem->get_sort_number()]))
            {
                array_splice( $this->array, $menuItem->get_sort_number(), 0, $menuItem );
                $this->adjust();
            }
            else
            {
                $this->array[$menuItem->get_sort_number()] = $menuItem;
            }

        }


    }

    /**
     * Syncs the sort numbers with the keys in the array
     */
    private function adjust()
    {
        foreach($this->array as $key => $menuItem)
        {
           $menuItem->set_sort_number($key);
        }
    }

    public function add_new($name, $link, $icon, $sort)
    {
        $tag = new DataTag();
        $tag->set_name("item" . sizeof($this->array));
        $tag->set_sort_number($sort);
        $tag->set_parent_id($this->itemsTag->get_id());
        $tag->create();
        $menuItem = new MenuItem($tag,$name, $link, $sort, $icon);
        $menuItem->save();
        return $menuItem;
    }

    /**
     * Removes the MenuItem at the specified Sort Number
     * @param $number
     */
    public function remove_by_sort_number($number)
    {
        unset($this->array[$number]);
    }

    /**
     * @return \app\libraries\theme\menu\item\MenuItem[]
     */
    public function getAsArray()
    {
        return $this->array;
    }

    public function save()
    {
        foreach($this->array as $menuItem)
        {
            $menuItem->save();
        }
    }


}