<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/18/2015
 * Time: 12:47 PM
 */

namespace app\libraries\theme\menu\item;


use app\libraries\database\DatabaseObject;
use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;

class MenuItem extends DatabaseObject
{
    private $datablocks = array();
    /**
     * @var DataTag $tag
     */
    private $tag = null;
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var DataBlock
     */
    private $link = null;
    /**
     * @var DataBlock
     */
    private $icon = null;
    /**
     * @var DataTag
     */
    private $linkTag;
    /**
     * @var DataTag
     */
    private $iconTag;

    /**
     * Creates a new MenuItem with the following arguments. The use of arugments is null but use all if used.
     * @param DataTag $menuItemTag
     */
    public function __construct($menuItemTag)
    {
            $this->tag = $menuItemTag;
            $this->id = $menuItemTag->get_id();
            $this->name = $menuItemTag->get_name();
            $this->linkTag = $menuItemTag->findChild("link");
            $this->iconTag = $menuItemTag->findChild("icon");
            $this->link = $this->linkTag->get_data_block();
            $this->icon = $this->iconTag->get_data_block();

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
        {
            $menutype = Types::create_type_tag("menu_item");
        }

        $property_type = Types::get_type_by_name("property",TypeCategory::getTagCategory() );
        if(!isset($property_type))
        {
            $property_type = Types::create_type_tag("property");
        }

        if(!isset($sort_number))
            $sort_number = 0;
        if(!isset($icon))
            $icon = "";

        $menuItemTag = new DataTag($name,$menu->get_id(), $menutype, $sort_number);
        $menuItemTag->create();


        $linkTag = new DataTag("link", $menuItemTag->get_id(), $property_type);
        $linkTag->create();
        $linkBlock = $linkTag->create_data_block();
        $linkBlock->set_value($link);
        $linkBlock->save();



        $iconTag = new DataTag("icon", $menuItemTag->get_id(), $property_type);
        $iconTag->create();
        $iconblock = $iconTag->create_data_block();
        $iconblock->set_value($icon);
        $iconblock->save();


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
     * Gets the href of the link. allows routes to be used
     * @return String
     */
    public function get_href()
    {
        if(\Route::has($this->link->getValue()))
        {
            return route($this->link->getValue());
        }
        else
        {
            return $this->link->getValue();
        }

    }

    /**
     * Returns null if an icon is not used
     * @return String
     */
    public function get_icon()
    {
        return $this->icon->getValue();
    }

    public function set_sort_number($number)
    {
        $this->tag->set_sort_number($number);
        $this->tag->save();
    }


    /**
     * sets the name or label of the menu link
     * @param $value
     */
    public function set_name($value)
    {
        $this->name = $value;
        $this->tag->set_name($value );
        $this->tag->save();
    }
    /**
     * Sets the href of the menu link
     * @param $value
     */
    public function set_href($value)
    {
         $this->link->set_value($value);
        $this->link->save();
    }
    /**
     * sets the icon html to be used
     * @param $value
     */
    public function set_icon($value)
    {
        $this->icon->set_value($value);
        $this->icon->save();
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
        {
           $this->tag->save();
            $this->link->save();
            $this->icon->save();
        }
    }

    /**
     * Gets the date at when the object was updated.
     * @return string
     */
    public function updated_at()
    {
        // TODO: Implement updated_at() method.
    }

    /**
     * Gets the date at when the object was created
     * @return string
     */
    public function created_at()
    {
        // TODO: Implement created_at() method.
    }
}