<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:13 PM
 */

namespace app\libraries\excel\templates\imports\suites;


use app\libraries\excel\Point;
use app\libraries\excel\templates\imports\tags\ImportRule;
use app\libraries\excel\templates\imports\tags\ImportTemplate;
use app\libraries\excel\templates\imports\tags\ImportTemplateSuite;
use app\libraries\excel\templates\imports\tags\TemplateCollection;
use app\libraries\types\Types;

/**
 * Class EmployeeTemplateSuite
 * @package app\libraries\excel\templates\imports\suites
 */
class EmployeeTemplateSuite
{

    /**
     * @return ImportTemplateSuite
     */
    public function construct()
    {
        $suite = new ImportTemplateSuite();
        $suite->setName("Labor Rate Import");


        $template_sheets = new  TemplateCollection(); //creates the collection that all of the templates will be added to


        /**
         * EG Corporate Projections
         */
        $mainSheet = new ImportTemplate("test");
        $mainSheet->getRules()->add(ImportRule::headerTag( new Point(1, 4), new Point(1,64), Types::get_type_row() ) );
        $mainSheet->getRules()->add(ImportRule::headerTag( new Point(2, 2), new Point(14, 2), Types::get_type_column()));
        $template_sheets->add($mainSheet);
        $suite->add($template_sheets);
        return $suite;
    }
}