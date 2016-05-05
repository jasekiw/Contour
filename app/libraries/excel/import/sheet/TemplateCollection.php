<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/1/2015
 * Time: 3:30 PM
 */

namespace app\libraries\excel\import\sheet;

use app\libraries\excel\import\suite\rule\ImportSuiteRule;

class TemplateCollection
{
    
    /** @var ImportTemplate[] $this ->array   */
    private $array = [];
    
    /** @var ImportSuiteRule[]   */
    private $rules = [];
    
    /**
     * @param $name
     *
     * @return ImportTemplate
     */
    public function find($name)
    {
        foreach ($this->array as $importTemplate) {
            if (strtoupper($name) == strtoupper($importTemplate->getSheet())) {
                return $importTemplate;
            }
        }
        return null;
    }
    
    /**
     * Check if the given sheet exists in the template collection
     *
     * @param string $name
     *
     * @return bool true | false
     */
    public function exists($name)
    {
        foreach ($this->array as $importTemplate)
            if ($name == $importTemplate->getSheet())
                return true;
        return false;
    }
    
    /**
     * @param ImportTemplate $importTemplate
     */
    public function add($importTemplate)
    {
        array_push($this->array, $importTemplate);
    }
    
    /**
     * @param String $name
     */
    public function remove($name)
    {
        foreach ($this->array as $importTemplate) {
            if ($name == $importTemplate->getSheet()) {
                unset($importTemplate);
            }
        }
    }
    
    /**
     * Gets all the Import Templates
     * @return ImportTemplate[]
     */
    public function getAll()
    {
        return $this->array;
    }
    
    /**
     * @param ImportSuiteRule $rule
     */
    public function addRule($rule)
    {
        array_push($this->rules, $rule);
    }
    
    /**
     * Detects if there is a rule for this sheet
     *
     * @param int|string $nameOrIndex
     *
     * @return bool
     */
    public function inRule($nameOrIndex)
    {
        if (is_int($nameOrIndex)) {
            foreach ($this->rules as $rule)
                if ($rule->type == ImportSuiteRule::RULE_TYPE_INDEX_ALIAS && $nameOrIndex >= $rule->startIndex && $nameOrIndex <= $rule->endIndex)
                    return true;
            return false;
        } else {
            foreach ($this->rules as $rule)
                if ($rule->type == ImportSuiteRule::RULE_TYPE_NAME_ALIAS && $rule->aliasName == $nameOrIndex)
                    return true;
            return false;
        }
    }
    
    /**
     * Gets the associated Rule
     *
     * @param int|string $nameOrIndex
     *
     * @return ImportSuiteRule | null
     */
    public function getRule($nameOrIndex)
    {
        if (is_int($nameOrIndex)) {
            foreach ($this->rules as $rule)
                if ($rule->type == ImportSuiteRule::RULE_TYPE_INDEX_ALIAS && $nameOrIndex >= $rule->startIndex && $nameOrIndex <= $rule->endIndex)
                    return $rule;
            return null;
        } else {
            foreach ($this->rules as $rule)
                if ($rule->type == ImportSuiteRule::RULE_TYPE_NAME_ALIAS && $rule->aliasName == $nameOrIndex)
                    return $rule;
            return null;
        }
    }
    
}