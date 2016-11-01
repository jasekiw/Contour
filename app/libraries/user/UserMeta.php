<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 9:47 PM
 */

namespace app\libraries\user;

use App\Models\User_Meta;
use Auth;
use app\libraries\contour\Contour;
use Config;

/**
 * Class UserMeta
 * @package app\libraries\user
 */
class UserMeta
{
    
    /**
     * Returns empty string if column not found
     *
     * @param String $var
     *
     * @return String
     */
    public static function get($var)
    {
        $answer = null;
        $alternate_answer = null;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($var == "date_joined")
            $alternate_answer = $user->created_at;
        else if ($var == "email")
            $alternate_answer = $user->email;
        else {
            
            $answer = User_Meta::where("user_id", "=", $user->id)->where("key", "=", $var)->first(['value']);
            if (!isset($answer))
                return ''; // returns an empty string if nothing found
            return $answer->value;
        }
        return $alternate_answer;
    }
    
    /**
     * Saves a file to the current user and moves it to the uploads folder
     *
     * @param $name
     *
     * @return string
     */
    public static function save_file($name)
    {
        if (\Input::hasFile($name)) {
            $filename = \Input::file($name)->getClientOriginalName();
            $uploadsDir = base_path(Config::get('constants.uploads_folder'));
            \Input::file($name)->move($uploadsDir, $filename);
            $uploadsUrl = Config::get('constants.uploads_url');
            self::save($name, $uploadsUrl . DIRECTORY_SEPARATOR . $filename);
            return $uploadsUrl . DIRECTORY_SEPARATOR . $filename;
        }
        return '';
    }
    
    /**
     * Saves a value to the current user under the key $name
     *
     * @param string $name
     * @param string $value
     */
    public static function save($name, $value)
    {
        if (!isset($value))
            return;
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $answer = null;
        $answer = User_Meta::where('user_id', '=', $user->id)->where("key", "=", $name)->first();
        
        if (isset($answer)) {
            $answer->value = $value;
            $answer->save();
        } else {
            $answer = new User_Meta();
            $answer->user_id = $user->id;
            $answer->key = $name;
            $answer->value = $value;
            $answer->save();
        }
    }
}