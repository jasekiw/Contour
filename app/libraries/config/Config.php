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
use App\Models\Tag;

class Config
{
    
    private $cachedPATH = null;
    private $cachedSystemTag = null;
    
    /**
     * Saves a file to the database and moves it to the uploads folder
     *
     * @param string $name
     */
    public function save_file($name)
    {
        
        if (\Input::hasFile($name)) {
            
            $filename = \Input::file($name)->getClientOriginalName();
            \Input::file($name)->move(Config::get('constants.uploads_folder'), $filename);
            $this->save($name, Config::get('constants.uploads_url') . DIRECTORY_SEPARATOR . $filename);
        }
    }
    
    /**
     * Grabs a configuration value by the name
     *
     * @param string      $var     The name for the configuration
     * @param string|null $default The default option to return if the value isn't found
     *
     * @return string The value grabbed
     */
    public function get($var, $default = null)
    {
        $answer = null;
        $answer = Configuration::where("key", "=", $var)->first(['value']);
        if (!isset($answer)) {
            
            return $default; // returns an empty string if nothing found
        }
        return $answer->value;
    }
    
    /**
     * Saves the value to the configuration at the $name
     *
     * @param string $name
     * @param string $value
     */
    public function save($name, $value)
    {
        if (!isset($value)) {
            return;
        }
        $answer = null;
        $answer = Configuration::where("key", "=", $name)->first();
        
        if (isset($answer)) {
            $answer->value = $value;
            $answer->save();
        } else {
            $answer = new Configuration();
            $answer->key = $name;
            $answer->value = $value;
            $answer->save();
        }
    }
    
    /**
     * Gets The DataTag That System Configuration is saved AtS
     * @return DataTag
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function get_system_tag()
    {
        if (isset($cachedSystemTag)) {
            return $this->cachedSystemTag;
        }
        $system_tag = DataTags::get_by_string("system", -1);
        if (isset($system_tag)) {
            $this->cachedSystemTag = $system_tag;
            return $system_tag;
        } else {
            $dataTag = new DataTag("system", -1, Types::get_type_folder());
            $dataTag->set_sort_number(1);
            $dataTag->create();
            $this->cachedSystemTag = $dataTag;
            return $dataTag;
        }
    }
    
    /**
     * Gets the tag id's that are considered in root
     * @return int[]
     */
    public function getPathTags()
    {
        if (isset($this->cachedPATH))
            return $this->cachedPATH;
        $path = $this->get("PATH");
        if ($path == null) {
            return $this->createPath();
        } else {
            $path = unserialize($path);
            $this->cachedPATH = $path;
            return $path;
        }
    }
    
    /**
     * @return int[]
     */
    private function createPath()
    {
        $reportsId = Tag::where('name', '=', 'reports')->where('type_id', '=', Types::get_type_folder()->get_id())->first()->id;
        $facilitiesId = Tag::where('name', '=', 'facilities')->where('type_id', '=', Types::get_type_folder()->get_id())->first()->id; //DataTags::get_by_string_and_type("facilities", Types::get_type_sheet());
        $path = [$reportsId, $facilitiesId, 0];
        $this->save("PATH", serialize($path));
        $this->cachedPATH = $path;
        return $path;
    }
    
    /**
     * Adds the tag id to the current path
     *
     * @param int $id
     */
    public function addPathId($id)
    {
        if (isset($this->cachedPATH)) {
            array_push($this->cachedPATH, $id);
            $this->save("PATH", serialize($this->cachedPATH));
            return;
        }
        
        $path = $this->get("PATH");
        if ($path == null) {
            $path = $this->createPath();
            array_push($path, $id);
            $this->cachedPATH = $path;
            $this->save("PATH", serialize($this->cachedPATH));
        } else {
            $path = unserialize($path);
            array_push($path, $id);
            $this->cachedPATH = $path;
            $this->save("PATH", serialize($this->cachedPATH));
        }
    }
    
    /**
     * Turns on all error reporting
     */
    public function turnOnErrorReporting()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }
    
    /**
     * Sets the execution time limit
     *
     * @param $seconds
     */
    public function setTimeLimit($seconds)
    {
        ini_set('max_execution_time', $seconds);
        set_time_limit($seconds);
    }
}