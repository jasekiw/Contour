<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 9:19 AM
 */

namespace app\libraries\excel\import\sheet;

use app\libraries\excel\Area;
use app\libraries\types\Type;
use app\libraries\excel\Point;
use app\libraries\types\Types;

/**
 * Class ImportRule. a rule that tells whether a cell is a tag or not
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportRule
{

    /**
     * @var string
     */
    const TAG_HEADER_FUNCTION = "HeaderTag";
    const TAG_CHILD_OF_FUNCTION = "ChildrenOf";
    const TAG_PROPERTY_FUNCTION = "TagProperty";
    const EXLUDE_FUNCTION = "Exclusion";
    const CELL_TWO_DIMENSION_TAG_FUNCTION = "TwoDimension";
    const CELL_ONE_DIMENSION_TAG_FUNCTION = "OneDimension";
    const ONE_DIMENSIONS_TAG_AXIS_X = "ONE_DIMENSIONS_TAG_AXIS_X";
    const ONE_DIMENSIONS_TAG_AXIS_Y = "ONE_DIMENSIONS_TAG_AXIS_Y";
    const CELL_ONE_TAG_FUNCTION = "CELL_ONE_TAG_FUNCTION";
    /**
     * @var Area
     */
    private $area;
    /**
     * @var Point $parent
     */
    private $parent = null;
    private $function = null;
    private $axis = null;
    /**
     * @var Type $type
     */
    private $type = null;

    /**
     * Creates new import rule with the specified function. ex. ImportRule::HEADER_TAG_FUNCTION
     * @param Area $area. the area that the rule should apply to
     * @param string $function
     */
    function __construct(Area $area, $function)
    {
        $this->area = $area;
        $this->function = $function;
    }

    /**
     * @param Area $area
     * @param Type $type
     * @return ImportRule
     */
    public static function createTagHeaderRule($area, $type)
    {
        $rule = new ImportRule($area, ImportRule::TAG_HEADER_FUNCTION);
        $rule->type = $type;
        return $rule;
    }

    /**
     * @param Area $area
     * @param Point $parent
     * @param Type $type
     * @return ImportRule
     */
    public static function createTagChildOfRule($area, $parent, $type)
    {
        $rule = new ImportRule($area, ImportRule::TAG_CHILD_OF_FUNCTION);
        $rule->parent = $parent;
        $rule->type = $type;
        return $rule;
    }

    /**
     * @param Area $area
     * @return ImportRule
     */
    public static function createExclusionRule(Area $area)
    {
        $rule = new ImportRule($area, ImportRule::EXLUDE_FUNCTION);
        return $rule;
    }

    /**
     * @param Area $area
     * @return ImportRule
     */
    public static function createTwoDimensionalCellRule(Area $area)
    {
        $rule = new ImportRule($area, ImportRule::CELL_TWO_DIMENSION_TAG_FUNCTION);
        $rule->type = Types::get_type_cell();
        return $rule;
    }

    /**
     * @param Area $area
     * @param Area $belongsTo the area where the headers are
     * @param string $axis The direction of the table. is the table horizontal or vertical? ImportRule::ONE_DIMENSIONS_TAG_AXIS_Y || ImportRule::ONE_DIMENSIONS_TAG_AXIS_X
     * @return ImportRule
     */
    public static function createOneDimensionalCellRule(Area $area, $belongsTo, $axis)
    {
        $rule = new ImportRule($area, ImportRule::CELL_ONE_DIMENSION_TAG_FUNCTION);
        $rule->type = Types::get_type_cell();
        $rule->axis = $axis;
        $rule->parent = $belongsTo;
        return $rule;
    }

    /**
     * Creates a enw rule for a one tag to cell basis
     * @param Point $point
     * @param Point $tagLocation
     * @return ImportRule
     */
    public static function createPropertyCellRule($point, $tagLocation)
    {
        $rule = new ImportRule($point->toArea(), ImportRule::CELL_ONE_TAG_FUNCTION);
        $rule->type = Types::get_type_cell();
        $rule->parent = $tagLocation;
        return $rule;
    }

    /**
     * Gets the parent location in the excel sheet
     *@return Point
     */
    public function getParent(){
        return $this->parent;
    }

    /**
     * Gets the starting row that this rule applies to
     * @return Point
     */
    public function getStarting(){
        return $this->area->getTopLeft();
    }

    /**
     * Gets the starting row that this rule applies to
     * @return Point
     */
    public function getEnding(){
        return $this->area->getBottomRight();
    }

    /**
     * Checks to see if the Point is in the current rule;
     * @param Point $point
     * @return bool
     */
    public function inRule(Point $point)
    {
        if($this->area->isWithin($point))
            return true;
        return false;
    }


    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the axis that a once dimension cell is tagged with
     * @return string
     */
    public function getAxis()
    {
        return $this->axis;
    }
}