<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:05 PM
 */

namespace app\libraries\excel\templates\imports\tags;


class TemplateSuiteManager
{

    private $templateSuitesCollection;

    public function __construct()
    {
        $this->templateSuitesCollection = new ImportTemplateSuiteCollection();
        $maintemplates = (new RuleConstruction())->construct()->getAll();
        $mainSuite = new ImportTemplateSuite();
        $mainSuite->add($maintemplates);
        $mainSuite->setName("Main Budget");
        $this->templateSuitesCollection->add($mainSuite);

    }
    public function getCollection()
    {
        return $this->templateSuitesCollection;
    }
}