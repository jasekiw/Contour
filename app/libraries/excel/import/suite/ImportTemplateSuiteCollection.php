<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 1:58 PM
 */

namespace app\libraries\excel\import\suite;


/**
 * Class ImportTemplateSuiteCollection
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportTemplateSuiteCollection
{

    /**
     * @var ImportTemplateSuite[]
     */
    private $suites = [];
    private $rule = [];

    /**
     * @param ImportTemplateSuite $suite
     */
    public function add($suite)
    {
        array_push($this->suites, $suite);
    }

    /**
     * @return ImportTemplateSuite[]
     */
    public function getAll()
    {
        return $this->suites;
    }

    /**
     * Gets the suite by name
     * @param $name
     * @return ImportTemplateSuite|null
     */
    function get($name)
    {
        foreach($this->suites as $suite)
            if($suite->getName() == $name)
                return $suite;
        return null;
    }
}