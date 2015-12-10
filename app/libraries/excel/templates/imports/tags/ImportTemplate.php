<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/1/2015
 * Time: 3:25 PM
 */

namespace app\libraries\excel\templates\imports\tags;
use app\libraries\excel\templates\imports\tags\ImportRulesCollection;

class ImportTemplate
{
    private $name = null;
    private $importRules = null;

    /**
     * @param String $name
     */
    function __construct($name)
    {
        $this->name = $name;
        $this->importRules = new ImportRulesCollection();
    }



    /**
     * @return String The name of the String
     */
    public  function getSheet()
    {
        return $this->name;
    }


    /**
     * @return ImportRulesCollection
     */
    public function getRules()
    {
        return $this->importRules;
    }
}