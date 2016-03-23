<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/30/2016
 * Time: 2:11 PM
 */

namespace app\libraries\excel\import\suite;


use app\libraries\tags\DataTag;

/**
 * Class SuiteTemplateAbstract
 * @package app\libraries\excel\import\suite
 */
abstract class SuiteTemplateAbstract
{
    protected $parentTag;
    /**
     * @param DataTag $parent
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function preRun($parent)
    {
        $this->parentTag = $parent;
    }
    /**
     * @return ImportTemplateSuite
     */
    public abstract function construct();
}