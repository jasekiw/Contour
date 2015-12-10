<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/2/2015
 * Time: 9:22 AM
 */

namespace app\libraries\excel;

/**
 * Class Point. Holds x and y coordinates
 * @package app\libraries\excel
 */
class Point
{
    /**
     * @var int
     * @access private
     */
    private $x = null;
    /**
     * @var int
     * @access private
     */
    private $y = null;


    /**
     * Constructs a Point from the coords
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Gets the x location of the point
     * @return int
     */
    public function getX() {
        return $this->x;
    }
    /**
     * Gets the y location of the point
     * @return int
     */
    public function getY(){
        return $this->y;
    }
}