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

class UserMeta
{

    /**
     * Returns empty string if column not found
     * @param String $var
     * @return String
     */
    public static function get($var)
    {
        $answer = null;
        $alternate_answer = null;
        if($var == "date_joined" )
        {
            $alternate_answer = Auth::user()->created_at;
        }
        else if($var == "email")
        {
            $alternate_answer = Auth::user()->email;
        }
        else {

            $answer = User_Meta::where("user_id", "=", Auth::user()->id)->where("key", "=", $var)->first(array('value'));
            if(!isset($answer))
            {
                return ''; // returns an empty string if nothing found
            }
            return $answer->value;
        }


        return $alternate_answer;

    }

    /**
     * Saves a value to the current user under the key $name
     * @param string $name
     * @param string $value
     */
    public static function save($name, $value)
    {
        if(!isset($value))
        {
            return;
        }
        $answer = null;
        $answer = User_Meta::where('user_id', '=', Auth::user()->id)->where("key", "=", $name)->first();

        if(isset($answer))
        {
            $answer->value = $value;
            $answer->save();
        }
        else
        {
            $answer = new User_Meta();
            $answer->user_id = Auth::user()->id;
            $answer->key = $name;
            $answer->value = $value;
            $answer->save();
        }
    }

    /**
     * Saves a file to the current user and moves it to the uploads folder
     * @param $name
     * @return string
     */
    public function save_file($name)
    {

        if(\Input::hasFile($name))
        {

            $filename = \Input::file($name)->getClientOriginalName();
            \Input::file($name)->move( \Contour::getConfigManager()->get('constants.uploads_folder'), $filename);
            $this->save($name, \Contour::getConfigManager()->get('constants.uploads_url') . DIRECTORY_SEPARATOR . $filename);
            return \Contour::getConfigManager()->get('constants.uploads_url') . DIRECTORY_SEPARATOR . $filename;
        }
        return '';
    }
}