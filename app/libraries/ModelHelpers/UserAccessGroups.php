<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/11/2016
 * Time: 4:47 PM
 */

namespace app\libraries\ModelHelpers;


use App\Models\User_Access_Group;

class UserAccessGroups
{
    /**
     * Returns an associative array of the user access groups.
     * id => name
     * @return array
     */
    public static function getAssociativeArray()
    {
        $result = [];
       $groups = User_Access_Group::all();
        foreach($groups as $group)
            $result[$group->id] =   $group->name;
        return $result;
    }

}