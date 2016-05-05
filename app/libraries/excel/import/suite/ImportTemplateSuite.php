<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:01 PM
 */

namespace app\libraries\excel\import\suite;

use app\libraries\excel\import\sheet\TemplateCollection;
use app\libraries\tags\DataTag;

/**
 * An Import suite is used to package import templates into one import process
 * Class ImportTemplateSuite
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportTemplateSuite
{
    
    /** @var DataTag   */
    private $baseTag;
    /** @var string   */
    private $name = "";
    /** @var TemplateCollection   */
    private $templateCollection;
    /** @var \Closure   */
    private $preImportMethod;
    /** @var mixed   */
    private $preImportObject;
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name for the import suite
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return TemplateCollection
     */
    public function getTemplateCollection()
    {
        return $this->templateCollection;
    }
    
    /**
     * @param TemplateCollection $templateCollection
     */
    public function setTemplateCollection($templateCollection)
    {
        $this->templateCollection = $templateCollection;
    }
    
    /**
     * @param \Closure | mixed $closureOrObject
     * @param null | string    $method
     */
    public function setPreImportMethod($closureOrObject, $method = null)
    {
        if ($method !== null) {
            $this->preImportObject = $closureOrObject;
            $this->preImportMethod = $method;
        } else
            $this->preImportMethod = $closureOrObject;
    }
    
    /**
     * @return \stdClass
     */
    public function runPreImportTasks()
    {
        if (!isset($this->preImportMethod))
            return new \stdClass();
        
        if (isset($this->preImportObject)) {
            $method = $this->preImportMethod;
            return $this->preImportObject->$method($this->getbaseTag());
        } else
            return $this->preImportMethod->call(new \stdClass());
    }
    
    /**
     * @return DataTag
     */
    public function getbaseTag()
    {
        return $this->baseTag;
    }
    
    /**
     * @param $baseTag
     */
    public function setBaseTag($baseTag)
    {
        $this->baseTag = $baseTag;
        foreach ($this->templateCollection->getAll() as $template) {
            if (!$template->parentFunctionSet()) {
                $template->setGetParentFunction(function () use (&$baseTag) {
                    return $baseTag;
                });
            }
        }
    }
    
}