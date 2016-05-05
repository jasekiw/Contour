<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/1/2015
 * Time: 3:25 PM
 */

namespace app\libraries\excel\import\sheet;

use app\libraries\tags\DataTag;

class ImportTemplate
{
    
    /**
     * Sheet mode needs
     */
    const IMPORT_MODE_SHEET = 1;
    const IMPORT_MODE_TABLE = 2;
    private $name = null;
    private $importRules = null;
    /** @var \Closure   */
    private $getParentFunction;
    private $getParentObject;
    private $mode;
    
    /**
     * @param String $name tha name of the sheet that this import template will use
     */
    function __construct($name)
    {
        $this->name = $name;
        $this->importRules = new ImportRulesCollection();
    }
    
    /**
     * Gets the sheet name for this import template
     * @return string The name of the Import Sheet
     */
    public function getSheet()
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
    
    /**
     * @return DataTag
     */
    public function getParentTag()
    {
        $function = $this->getParentFunction;
        if (isset($this->getParentObject)) {
            $method = $this->getParentFunction;
            return $this->getParentObject->$method();
        } else {
            return $function();
        }
    }
    
    /**
     * Sets the function to get the parent.
     *
     * @param \Closure | \Object | mixed $closureOrObject
     * @param null | string              $method
     */
    public function setGetParentFunction($closureOrObject, $method = null)
    {
        $this->getParentObject = null;
        $this->getParentFunction = null;
        if ($method != null) {
            $this->getParentObject = $closureOrObject;
            $this->getParentFunction = $method;
        } else
            $this->getParentFunction = $closureOrObject;
    }
    
    /**
     * @return bool
     */
    public function parentFunctionSet()
    {
        return isset($this->getParentObject) || isset($this->getParentFunction);
    }
}