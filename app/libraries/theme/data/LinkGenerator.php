<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/11/2016
 * Time: 10:35 PM
 */

namespace app\libraries\theme\data;


class LinkGenerator
{

    /**
     * @param string $route The name of the route to generate the links from
     * @param string $title The title property of the resource
     * @param string $linkid The property name that should be used for the id
     * @param string $letter The current letter
     * @param array $resources an array of database objects
     */
    public static function setupLinksAtoZ($view, $route, $title, $linkid, $letter, $resources )
    {
        $items = [];
        if(isset($letter) && $letter !== "")
        {

            foreach($resources as $item)
            {
                if(strtoupper(substr($item->$title,0,1)) == $letter)
                {
                    $userToAdd = new \stdClass();
                    $userToAdd->title = $item->$title;
                    $userToAdd->link = route($route,  [$item->$linkid]);
                    array_push($items, $userToAdd);
                }

            }
            $view->current = $letter;
            $view->letter = $letter;
        }
        else
        {
            $view->current = "all";

            foreach($resources as $item)
            {
                $userToAdd = new \stdClass();
                $userToAdd->title = $item->$title;
                $userToAdd->link = route($route, [$item->$linkid]);
                array_push($items, $userToAdd);
            }
        }
        $view->items = $items;
    }

    /**
     * @param mixed $view The view to add links to
     * @param string $route
     */
    public static function generateAlphabetLinks($view, $route)
    {
        $alphabetLinks = [];
        $alphabet = range('A', 'Z');
        foreach($alphabet as $alpha)
            $alphabetLinks[$alpha] = route($route, [$alpha]);
        $view->alphabet = $alphabetLinks;
    }
}