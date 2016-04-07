<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 1:58 PM
 */

namespace app\libraries\excel\templates\imports\tags;


/**
 * Class ImportTemplateSuiteCollection
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportTemplateSuiteCollection
{

    private $suites = [];

    public function add($suite)
    {
        array_push($this->suites, $suite);
    }
    public function getAll()
    {
        return $this->suites;
    }
}