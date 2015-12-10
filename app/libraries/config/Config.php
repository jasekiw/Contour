<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 10/13/2015
 * Time: 9:38 PM
 */

namespace app\libraries\config;


use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use App\Models\Configuration;

class Config
{

    private $cachedSystemTag = null;

    /**
     * Grabs a configuration value by the name
     * @param string $var The name for the configuration
     * @param string|null $default The default option to return if the value isn't found
     * @return string The value grabbed
     */
    public function get($var, $default = null)
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
     * Saves the value to the configuration at the $name
     * @param string $name
     * @param string $value
     */
    public function save($name, $value)
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

    /**
     * Saves a file to the database and moves it to the uploads folder
     * @param string $name
     */
    public function save_file($name)
    {

        if(\Input::hasFile($name))
        {

            $filename = \Input::file($name)->getClientOriginalName();
            \Input::file($name)->move( Config::get('constants.uploads_folder'), $filename);
            $this->save($name, Config::get('constants.uploads_url') . DIRECTORY_SEPARATOR . $filename);
        }
    }


    /**
     * Gets The DataTag That System Configuration is saved AtS
     * @return DataTag
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function get_system_tag()
    {
        if(isset($cachedSystemTag))
        {
            return $this->cachedSystemTag;
        }
        $system_tag = DataTags::get_by_string("system", -1);
        if(isset($system_tag))
        {
            $this->cachedSystemTag = $system_tag;
            return $system_tag;
        }
        else
        {
            $dataTag = new DataTag("system", -1, Types::get_type_folder());
            $dataTag->set_sort_number(1);
            $dataTag->create();
            $this->cachedSystemTag = $dataTag;
            return $dataTag;
        }
    }

}