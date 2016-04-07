<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:13 PM
 */

namespace app\libraries\excel\import\suite\templates;


use app\libraries\excel\import\suite\SuiteTemplateAbstract;
use app\libraries\excel\Point;
use app\libraries\excel\Area;
use app\libraries\excel\import\sheet\ImportRule;
use app\libraries\excel\import\sheet\ImportTemplate;
use app\libraries\excel\import\suite\ImportTemplateSuite;
use app\libraries\excel\import\sheet\TemplateCollection;
use app\libraries\tags\DataTag;
use app\libraries\types\Types;

/**
 * Class LaborRateTemplateSuite
 * @package app\libraries\excel\import\suite\templates
 */
class LaborRateTemplateSuite extends SuiteTemplateAbstract
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
         * test
         */
        $mainSheet = new ImportTemplate("test");
        $headerRow = new Area(new Point(4, 2), new Point(12, 2));
        $specialRows = new Area(new Point(4, 48), new Point(4, 56));
        $mainSheet->getRules()->add(ImportRule::createTagHeaderRule($headerRow, Types::get_type_table_header()));
//        $mainSheet->getRules()->add(ImportRule::createTagHeaderRule((new Point(13,2))->toArea(), Types::get_type_property()));
        $mainSheet->getRules()->add(ImportRule::createOneDimensionalCellRule(new Area(new Point(4,3), new Point(12, 47) ), $headerRow, ImportRule::ONE_DIMENSIONS_TAG_AXIS_Y  ));
      

        $mainSheet->getRules()->add(ImportRule::createTagHeaderRule($specialRows, Types::get_type_row()));
        $mainSheet->getRules()->add(ImportRule::createTwoDimensionalCellRule(new Area(new Point(10, 48), new Point(12, 56) )));
        /**
         * created tag per minute cost total to tag the total of per minute cost. if this doesnt exist at (13,55) then add it
         */
        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(13,56), new Point(13,55)));

        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(13,3), new Point(13,2)));
        $template_sheets->add($mainSheet);
        $suite->setTemplateCollection($template_sheets);
        return $suite;
    }

}