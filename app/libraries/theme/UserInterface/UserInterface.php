<?php
namespace app\libraries\theme\userInterface;

use app\libraries\theme\Theme;

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/13/2015
 * Time: 11:55 PM
 */
class UserInterface
{
    
    /**
     * @param      $name
     * @param      $savelocation
     * @param      $value
     * @param      $type
     * @param      $title
     * @param null $placeholder
     *
     * @return string
     */
    private static $uniqueNumber = 0;
    
    public static function editable($name, $savelocation, $value, $type, $title, $placeholder = null)
    {
        $attrib = "";
        $value_inside = "";
        if (isset($placeholder)) {
            $attrib = ' placeholder="' . $placeholder . '" ';
        }
        
        return ' <span class="data-value"><a href="#" id="' . $name . '" data-type="' . $type . '" data-pk="1" data-url="' . $savelocation . '" data-title="' . $title . '"  class="editable editable-click"  data-original-title="" title=""  ' . $attrib . '>' . $value . '</a></span>';
    }
    
    public static function mini_drop_zone($name)
    {
        return '<div class="mini_dropzone_container"><div id="dropzone" class="' . $name . '">
                    <div>dropzone</div>
                    <input type="file" accept="image/png, application/pdf" />
                </div></div>';
    }
    
    public static function makeContextMenu($menues)
    {
        $id = "contextMenu" . self::getUnique();
        $reponse = '';
        $reponse .= '
        <div id="' . $id . '">
            <ul class="dropdown-menu" role="menu">';
        
        foreach ($menues as $menue) {
            $reponse .= '<li><a class="context_menu_item" tabindex="-1" href="' . $menue['url'] . '">' . $menue['title'] . '</a></li>';
        }
        
        $reponse .= '
            </ul>
        </div>';
        Theme::addFootertask($reponse);
        return $id;
    }
    
    private static function getUnique()
    {
        $number = self::$uniqueNumber;
        self::$uniqueNumber += 1;
        return $number;
    }
    
}