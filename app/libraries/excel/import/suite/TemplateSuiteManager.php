<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:05 PM
 */

namespace app\libraries\excel\import\suite;
use app\libraries\excel\import\suite\templates\LaborRateTemplateSuite;
use app\libraries\excel\import\suite\templates\MainBudgetTemplateSuite;


/**
 * Class TemplateSuiteManager
 * @package app\libraries\excel\templates\imports\tags
 */
class TemplateSuiteManager
{

    private $templateSuitesCollection;

    /**
     * Inserts Suites
     * TemplateSuiteManager constructor.
     */
    public function __construct()
    {
        $this->templateSuitesCollection = new ImportTemplateSuiteCollection();
        $this->templateSuitesCollection->add(( new LaborRateTemplateSuite() )->construct());
        $this->templateSuitesCollection->add(( new MainBudgetTemplateSuite() )->construct());
    }

    /**
     * @return ImportTemplateSuiteCollection
     */
    public function getSuiteCollection()
    {
        return $this->templateSuitesCollection;
    }
}