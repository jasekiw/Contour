<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 9:18 AM
 */

namespace app\libraries\excel\templates\imports\tags;


use app\libraries\excel\Point;

/**
 * This class holds a collection of import rules along with useful methods to apply import logic
 * Class ImportRulesCollection
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportRulesCollection
{
    /**
     * The import rules
     * @var ImportRule[]
     * @access private
     */
    private $importRules = array();


    /**
     * adds an ImportRule object
     * All import rules start at 1 instead of 0
     * @param ImportRule $importRule
     */
    public function add($importRule)
    {
        array_push($this->importRules, $importRule);
    }

    /**
     * adds an array of ImportRule objects
     * @param ImportRule[] $importRules
     */
    public function addAll($importRules)
    {
        $this->$importRules = array_merge($this->$importRules,$importRules );
    }


    /**
     * Removes element at the specific index
     * @param int $index
     * @return bool Returns true if the element was removed
     */
    public function remove($index)
    {
        if(sizeOf($this->importRules) > 0)
        {
            unset($this->importRules[$index]);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Removes the last element from the array
     * @return bool Returns true if the element was removed
     */
    public function removeLast()
    {
        if(sizeOf($this->importRules) > 0)
        {
            array_pop($this->importRules);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Removes first element from the array
     * @return bool Returns true if the element was removed
     */
    public function removeFirst()
    {
        if(sizeOf($this->importRules) > 0)
        {
            array_shift($this->importRules);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Gets all of the Import Rules as am array
     * @return ImportRule[]
     */
    public function getAll()
    {
        return $this->importRules;
    }

    /**
     * Gets an import rule at the specified index
     * @param $index
     * @return ImportRule
     */
    public function get($index)
    {
        return $this->importRules[$index];
    }

    /**
     * Gets the size of the array of ImportRules
     * @return int
     */
    public function getSize()
    {
        return sizeof($this->importRules);
    }


    /**
     * Checks if the array of ImportRules is empty
     * @return bool
     */
    public function isEmpty()
    {
        return sizeof($this->importRules) == 0;
    }

    /**
     * Checks if the given x and y coordinates are in any of the rules
     * @param int $x
     * @param int $y
     * @return bool Returns true if the coords are in any of the rules.
     */
    public function InAnyRules($x, $y)
    {
        foreach($this->importRules as $rule)
        {

            if( $rule->inRule(new Point($x,$y)) )
            {
                return true;
            }

        }
        return false;
    }


    /**
     * Gets the rule that the coords fall in. cildren of rule takes priority in the return
     * @param integer $x
     * @param integer $y
     * @return ImportRule
     */
    public function getRuleIn($x, $y)
    {
        $array = array();
        $child_rules = array();
        foreach($this->importRules as $rule)
        {

            if($rule->inRule(new Point($x,$y)) )
            {
                array_push($array, $rule);
                if($rule->getFunction()->getName() == ImportRuleFunction::getChildOf()->getName())
                {
                    array_push($child_rules, $rule);
                }
            }

        }
        if(sizeOf($child_rules) > 0)
        {
            return $child_rules[sizeOf($child_rules) - 1];
        }

        if(sizeOf($array) > 0) {
            return $array[sizeOf($array) - 1];
        }
        return null;
    }


    /**
     * Checks if the given coords are in an exluded area
     * @param int $x
     * @param int $y
     * @return bool Returns true if the coords are in an excluded area
     */
    public function inExludedArea($x, $y)
    {
        foreach($this->importRules as $rule)
        {

            if($rule->inRule(new Point($x,$y)) )
            {

               if($rule->getFunction()->getName() == ImportRuleFunction::getExclude()->getName())
               {

                    return true;
               }
            }

        }
        return false;
    }
}