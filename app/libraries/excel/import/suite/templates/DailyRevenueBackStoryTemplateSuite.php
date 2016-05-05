<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/7/2016
 * Time: 2:44 AM
 */

namespace app\libraries\excel\import\suite\templates;

use app\libraries\excel\Area;
use app\libraries\excel\import\sheet\ImportRule;
use app\libraries\excel\import\sheet\ImportTemplate;
use app\libraries\excel\import\sheet\TemplateCollection;
use app\libraries\excel\import\suite\ImportTemplateSuite;
use app\libraries\excel\import\suite\SuiteTemplateAbstract;
use app\libraries\excel\Point;
use app\libraries\types\Types;

/**
 * Class DailyRevenueBackStoryTemplateSuite
 * @package app\libraries\excel\import\suite\templates
 */
class DailyRevenueBackStoryTemplateSuite extends SuiteTemplateAbstract
{
    
    /**
     * @return ImportTemplateSuite
     */
    public function construct()
    {
        $suite = new ImportTemplateSuite();
        $suite->setName("Daily Revenue Back Story");
        $template_sheets = new  TemplateCollection(); //creates the collection that all of the templates will be added to
        /**
         * test
         */
        $mainSheet = new ImportTemplate("backstory");
        
        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(3, 5), new Point(3, 4)));
        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(3, 7), new Point(3, 6)));
        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(3, 9), new Point(3, 8)));
        $mainSheet->getRules()->add(ImportRule::createPropertyCellRule(new Point(3, 7), new Point(3, 6)));
//        $mainSheet->getRules()->add(ImportRule::createTagHeaderRule($headerRow, Types::get_type_table_header()));
//        $mainSheet->getRules()->add(ImportRule::createTagHeaderRule((new Point(13,2))->toArea(), Types::get_type_property()));
//        $mainSheet->getRules()->add(ImportRule::createOneDimensionalCellRule(new Area(new Point(4,3), new Point(12, 47) ), $headerRow, ImportRule::ONE_DIMENSIONS_TAG_AXIS_Y  ));
        
        $template_sheets->add($mainSheet);
        $suite->setTemplateCollection($template_sheets);
        return $suite;
    }
}