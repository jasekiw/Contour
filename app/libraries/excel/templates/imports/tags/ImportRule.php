<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 9:19 AM
 */

namespace app\libraries\excel\templates\imports\tags;

use app\libraries\types\Type;
use app\libraries\excel\Point;

/**
 * Class ImportRule. a rule that tells whether a cell is a tag or not
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportRule
{

    /***
     * @var int $from
     */
    private $from = null;
    /**
     * @var int $to
     */
    private $to = null;
    /**
     * @var Point $parent
     */
    private $parent = null;
    private $function = null;
    /**
     * @var Type $type
     */
    private $type = null;

    /**
     * @param Point $from
     * @param Point $to
     * @param ImportRuleFunction $function
     */
    function __construct($from, $to, $function)
    {
        //$this->parent = $parent;
        $this->from = $from;
        $this->to = $to;
//        if(strtoupper($axis) == "Y" || strtoupper($axis) == "X")
//        {
//            $this->axis = strtoupper($axis);
//        }
//        else
//        {
//            $this->axis = "X";
//        }

        $this->function = $function;

    }

    /**
     * @param Point $from
     * @param Point $to
     * @param Type $type
     * @return ImportRule
     */
    public static function headerTag($from, $to, $type)
    {
        $rule = new ImportRule($from, $to, ImportRuleFunction::getHeaderTagFunction());
        $rule->type = $type;
        return $rule;
    }

    /**
     * @param Point $from
     * @param Point $to
     * @param Point $parent
     * @param Type $type
     * @return ImportRule
     */
    public static function createChildOf($from, $to, $parent, $type)
    {
        $rule = new ImportRule($from, $to, ImportRuleFunction::getChildOf());
        $rule->parent = $parent;
        $rule->type = $type;
        return $rule;
    }

    /**
     * @param Point $from
     * @param Point $to
     * @return ImportRule
     */
    public static function createExclusion($from, $to)
    {
        $rule = new ImportRule($from, $to, ImportRuleFunction::getExclude());
        return $rule;
    }

    /**
     * Gets the parent location in the exel sheet
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
        return $this->from;
    }

    /**
     * Gets the starting row that this rule applies to
     * @return Point
     */
    public function getEnding(){
        return $this->to;
    }

    /**
     * Checks to see if the Point is in the current rule;
     * @param Point $point
     * @return bool
     */
    public function inRule(Point $point)
    {
        if($point->getX() >= $this->getStarting()->getX() && $point->getX() <= $this->getEnding()->getX() &&
            $point->getY() >= $this->getStarting()->getY() && $point->getY() <= $this->getEnding()->getY())
        {
            return true;
        }
        return false;
    }


    /**
     * @return ImportRuleFunction
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
}