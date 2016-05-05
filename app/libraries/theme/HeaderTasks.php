<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 11:11 AM
 */

namespace app\libraries\theme;

use app\libraries\modelHelpers\ConfigHelper;
use View;
use Auth;
use App\Models\User_Meta;

/**
 * Class HeaderTasks
 * @package app\libraries\theme
 */
class HeaderTasks
{
    
    /**
     * adds global variables for views
     */
    function perform()
    {

//        if(\App::runningInConsole())
//           return;
        $developer = "Jason Gallavin";
        $company_name = ConfigHelper::get('company_name', '');
        $logo_url = ConfigHelper::get('logo', asset("assets/img/logo.png"));
        $website_name = ConfigHelper::get('website_name', "Management Dashboard");
        $website_description = ConfigHelper::get('website_description', $company_name . " " . $website_name);
        $favicon_url = ConfigHelper::get('favicon', asset('assets/ico/favicon.ico'));
        $user_access_group = "administrators";
        //sharing the variables
        View::share('user_access_group', $user_access_group);
        View::share('favicon_url', $favicon_url);
        View::share("website_name", $website_name);
        View::share("website_description", $website_description);
        View::share("developer", $developer);
        View::share('copyright_html', '<footer class="footer">&copy; ' . date('Y') . ' ' . $company_name . '</footer>');
        View::share('logo_url', $logo_url);
        View::share('company_name', $company_name);
        //if logged in
        
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user_id = $user->id;
            $username = $user->username;
            
            $user_pic = User_Meta::where('user_id', '=', $user_id)->where('key', '=', 'profile_pic')->first(['value']);
            if (!isset($user_pic)) {
                $user_pic = asset('assets/img/default/no-user-profile-pic.png');
            } else {
                $user_pic = $user_pic->value;
                View::share('user_pic', $user_pic);
            }
            
            View::share('user_default_pic', asset('assets/img/default/no-user-profile-pic.png'));
            View::share('user_id', $user->id);
            View::share('username', $username);
            
            View::share('isAdmin', $user->user_access_group_id == 1 ? true : false);
        } else {
            View::share('user_id', -1);
            View::share('username', '');
        }
    }
}