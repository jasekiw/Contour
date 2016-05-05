<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/11/2016
 * Time: 11:10 PM
 */

namespace app\libraries\useraccess;

use Auth;

class UserAccessControl
{
    
    /**
     * Checks if the user is admin
     * @return bool
     */
    public static function isAdmin()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $groupID = $user->user_access_group_id;
        return $groupID == 1;
    }
}