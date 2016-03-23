<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/1/2015
 * Time: 3:30 PM
 */

namespace app\libraries\excel\templates\imports\tags;


class TemplateCollection
{
    /**
     * @var ImportTemplate[] $this->array
     */
    private $array = array();

    /**
     * @param $name
     * @return ImportTemplate
     */
    public function find($name)
    {
        foreach($this->array as $importTemplate)
        {
           if(strtoupper($name) == strtoupper($importTemplate->getSheet()))
           {
               return $importTemplate;
           }
        }
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name)
    {
        foreach($this->array as $importTemplate)
        {
            if($name == $importTemplate->getSheet())
            {
                return true;
            }
        }
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
        foreach($this->array as $importTemplate)
        {
            if($name == $importTemplate->getSheet())
            {
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
}