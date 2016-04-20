<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/18/2015
 * Time: 12:47 PM
 */

namespace app\libraries\theme\menu\item;

use app\libraries\database\DatabaseObject;
use app\libraries\tags\DataTag;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;

/**
 * Class MenuItem
 * @package app\libraries\theme\menu\item
 */
class MenuItem extends DatabaseObject
{

    /**
     * @var DataTag $tag
     */
    private $tag = null;
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var string
     */
    private $link = null;
    /**
     * @var string
     */
    private $icon = null;


    /**
     * Cconstructs a MenuItem with the following arguments. The use of arugments is null but use all if used.
     * @param DataTag $menuItemTag
     */
    public function __construct($menuItemTag)
    {
            $this->tag = $menuItemTag;
            $this->id = $menuItemTag->get_id();
            $this->name = $menuItemTag->getNiceName();
            $this->link = $menuItemTag->getMetaValue("link");
            $this->icon = $menuItemTag->getMetaValue("icon");
    }

    /**
     * Creates a new MenuItem with the following arguments. The use of arugments is null but use all if used.
     * @param DataTag $menu
     * @param String $name
     * @param String $link
     * @param null $sort_number
     * @param String $icon
     * @return MenuItem
     */
    public static function make($menu, $name , $link , $sort_number = null,  $icon = null)
    {

        $menutype = Types::get_type_by_name("menu_item",TypeCategory::getTagCategory() );
        if(!isset($menutype))
            $menutype = Types::create_type_tag("menu_item");
        $property_type = Types::get_type_by_name("property",TypeCategory::getTagCategory() );
        if(!isset($property_type))
            $property_type = Types::create_type_tag("property");
        if(!isset($sort_number))
            $sort_number = 0;
        if(!isset($icon))
            $icon = "";

        $menuItemTag = new DataTag($name,$menu->get_id(), $menutype, $sort_number);
        $menuItemTag->create();
        $menuItemTag->setNiceName($name);
        $menuItemTag->setMetaValue("link", $link);
        $menuItemTag->setMetaValue("icon", $icon);
        $menuItem = new MenuItem($menuItemTag);
        return $menuItem;
    }


    /**
     * Gets the Name or Label of the menu Item
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the name or label of the menu link
     * @param $value
     */
    public function set_name($value)
    {
        $this->name = $value;
        $this->tag->set_name($value );
        $this->tag->setNiceName($value);
        $this->tag->save();
    }

    /**
     * Gets the Tag Name of the menu
     * @return string
     */
    public function getTagName()
    {
        return $this->tag->get_name();
    }

    /**
     * Gets the href of the link. allows routes to be used
     * @return String
     */
    public function get_href()
    {
        if(\Route::has($this->link))
            return route($this->link);
        return $this->link;
    }

    /**
     * Returns null if an icon is not used
     * @return String
     */
    public function get_icon()
    {
        return $this->icon;
    }

    /**
     * sets the icon html to be used
     * @param $value
     */
    public function set_icon($value)
    {
        $this->icon = $value;
        $this->tag->setMetaValue("icon", $value);
    }

    public function set_sort_number($number)
    {
        $this->tag->set_sort_number($number);
        $this->tag->save();
    }

    /**
     * Sets the href of the menu link
     * @param $value
     */
    public function set_href($value)
    {
        $this->link = $value;
        $this->tag->setMetaValue("link", $value);
    }

    /**
     * @return int
     */
    public function get_sort_number()
    {
        return $this->tag->get_sort_number();
    }

    public function save()
    {
        if(isset($this->id))
           $this->tag->save();
    }

    /**
     * Gets the menu ID
     * @return int
     */
    public function getMenuID()
    {
        return $this->tag->get_parent()->get_parent_id();
    }

    /**
     * Gets the date at when the object was updated.
     * @return string
     */
    public function updated_at()
    {
        return $this->tag->updated_at();
    }

    /**
     * Gets the date at when the object was created
     * @return string
     */
    public function created_at()
    {
        return $this->tag->created_at();
    }

    public function delete()
    {
        $this->tag->delete();
    }
}