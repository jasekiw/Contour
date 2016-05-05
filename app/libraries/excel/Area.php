<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 3/28/2016
 * Time: 11:28 AM
 */

namespace app\libraries\excel;

/**
 * Class Area
 * @package app\libraries\excel\templates\imports
 */
class Area
{
    
    private $topLeft;
    private $bottomRight;
    
    /**
     * Creates a new area off of the two points
     *
     * @param Point $topLeft
     * @param Point $bottomRight
     */
    public function __construct(Point $topLeft, Point $bottomRight)
    {
        $this->topLeft = $topLeft;
        $this->bottomRight = $bottomRight;
    }
    
    /**
     * @param Point $point
     *
     * @return Area
     */
    public static function fromPoint($point)
    {
        return new Area($point, $point);
    }
    
    /**
     * @return Point
     */
    public function getTopLeft()
    {
        return $this->topLeft;
    }
    
    /**
     * Gets the bottom right point of the rectangle
     * @return Point
     */
    public function getBottomRight()
    {
        return $this->bottomRight;
    }
    
    /**
     * Checks to see if the given point is whithin this area.
     *
     * @param Point $point The point to check
     *
     * @return bool true if the point is within the area.
     */
    public function isWithin($point)
    {
        if ($point->getX() >= $this->topLeft->getX() && $point->getX() <= $this->bottomRight->getX() &&
            $point->getY() >= $this->topLeft->getY() && $point->getY() <= $this->bottomRight->getY()
        )
            return true;
        return false;
    }
}