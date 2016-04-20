<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/13/2015
 * Time: 10:34 PM
 */
namespace app\libraries\modelHelpers;

use App\Models\Configuration;
use Config;
use Input;


/**
 * Class ConfigHelper
 */
class ConfigHelper
{

    /**
     * Grabs a configuration value by the name
     * @param string $var The name for the configuration
     * @param string|null $default The default option to return if the value isn't found
     * @return string The value grabbed
     */
    public static function get($var, $default = null)
    {
        $answer = null;
        $answer = Configuration::where("key", "=", $var)->first(array('value'));
        if(!isset($answer))
        {
            if(isset($default))
            {
                return $default;
            }
            return ''; // returns an empty string if nothing found
        }
        return $answer->value;
    }

    /**
     * Saves a file to the database and moves it to the uploads folder
     * @param string $name
     */
    public static function save_file($name)
    {
        if(!Input::hasFile($name))
            return;
        $filename = Input::file($name)->getClientOriginalName();
        $uploadsFolder = Config::get('constants.uploads_folder');
        $uplodsUrl = Config::get('constants.uploads_url');
        Input::file($name)->move( \Session::get('uploads_folder'), $filename);
        ConfigHelper::save($name, \Session::get('uploads_url') . DIRECTORY_SEPARATOR . $filename);

    }

    /**
     * Saves the value to the configuration at the $name
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
        $answer = Configuration::where("key", "=", $name)->first();

        if(isset($answer))
        {
            $answer->value = $value;
            $answer->save();
        }
        else
        {
            $answer = new Configuration();
            $answer->key = $name;
            $answer->value = $value;
            $answer->save();
        }

    }


}