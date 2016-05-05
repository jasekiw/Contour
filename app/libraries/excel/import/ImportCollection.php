<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 8/28/2015
 * Time: 4:19 PM
 */

namespace app\libraries\excel\import;

use app\libraries\excel\import\ImportUnit;

/**
 * Class ImportCollection
 * @package app\libraries\excel\templates\imports
 */
class ImportCollection
{
    
    /** @var ImportUnit[] $importUnits   */
    private $importUnits = [];
    
    function __construct()
    {
    }
    
    /**
     * @param ImportUnit $importUnit
     */
    public function add($importUnit)
    {
        array_push($this->importUnits, $importUnit);
    }
    
    /**
     * add an array of ImportUnit objects
     *
     * @param array $importUnits
     */
    public function addAll($importUnits)
    {
        array_push($this->importUnits, $importUnit);
    }
    
    /**
     * Removes element at the specific index
     *
     * @param integer $index
     *
     * @return bool Sucessfull, false if array is already empty.
     */
    public function remove($index)
    {
        if (sizeof($this->importUnits) > 0) {
            unset($this->importUnits[$index]);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Removes the last element from the array
     * @return bool returns true if it succesffuly removed last element on the array. if the array is empty, false is
     *              returned.
     */
    public function removeLast()
    {
        if (sizeof($this->importUnits) > 0) {
            array_pop($this->importUnits);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Removes first element from the array
     *
     * @return bool
     */
    public function removeFirst()
    {
        if (sizeof($this->importUnits) > 0) {
            array_shift($this->importUnits);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return ImportUnit[]
     */
    public function getAll()
    {
        return $this->importUnits;
    }
    
    /**
     * @param $index
     *
     * @return ImportUnit
     */
    public function get($index)
    {
        return $this->importUnits[$index];
    }
    
    /**
     * @return int
     */
    public function getSize()
    {
        return sizeof($this->importUnits);
    }
}