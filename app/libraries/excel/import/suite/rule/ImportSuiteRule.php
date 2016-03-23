<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/24/2016
 * Time: 11:05 AM
 */

namespace app\libraries\excel\import\suite\rule;


/**
 * Class ImportSuiteRule
 * @package app\libraries\excel\templates\imports\suite\rule
 */
class ImportSuiteRule
{
    const RULE_TYPE_INDEX_ALIAS = 1;
    const RULE_TYPE_NAME_ALIAS = 1;
    public $startIndex;
    public $endIndex;
    public $templateSheet;
    public $type;

    public $aliasName;
    /**
     * Lets sheets with the indices to be considered for another import sheet.
     * @param $from
     * @param $to
     * @param $templateSheet
     * @return ImportSuiteRule
     */
    public static function createAliasIndexRule($from, $to, $templateSheet)
    {
        $rule = new ImportSuiteRule();
        $rule->endIndex = $to;
        $rule->startIndex = $from;
        $rule->templateSheet = $templateSheet;
        $rule->type = self::RULE_TYPE_INDEX_ALIAS;
        return $rule;
    }
    public static function createAliasNameRule($name, $templateSheet)
    {
        $rule = new ImportSuiteRule();
        $rule->aliasName = $name;
        $rule->templateSheet = $templateSheet;
        $rule->type = self::RULE_TYPE_NAME_ALIAS;
        return $rule;
    }

}