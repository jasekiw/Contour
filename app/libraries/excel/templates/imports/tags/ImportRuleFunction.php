<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 10:22 AM
 */

namespace app\libraries\excel\templates\imports\tags;


class ImportRuleFunction
{
    private $function = null;



    /**
     * @return ImportRuleFunction
     */
    public static function getHeaderTagFunction(){
        $function = new ImportRuleFunction();
        $function->function = "HeaderTag";
        return $function;
    }

    /**
     * @return ImportRuleFunction
     */
    public static function getChildOf(){
        $function = new ImportRuleFunction();
        $function->function = "ChildrenOf";
        return $function;
    }


    /**
     * @return ImportRuleFunction
     */
    public static function getExclude(){
        $function = new ImportRuleFunction();
        $function->function = "Exclusion";
        return $function;
    }


    /**
     * @return String
     */
    public function getName(){
        return $this->function;
    }

}