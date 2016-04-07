<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 2:01 PM
 */

namespace app\libraries\excel\templates\imports\tags;


class ImportTemplateSuite
{
    /**
     * @var string
     */
    private $name = "";
    /**
     * @var TemplateCollection[]
     */
    private $templateCollections = [];
    public function add($template)
    {
        array_push($this->templateCollections,$template );
    }

    /**
     * @return TemplateCollection[]
     */
    public function getTemplates()
    {
        return $this->templateCollections;
    }

    public function getName($name)
    {
        return $this->name;
    }

    /**
     * Sets the name for the import suite
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}