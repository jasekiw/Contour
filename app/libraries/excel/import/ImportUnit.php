<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 8/28/2015
 * Time: 4:03 PM
 */

namespace app\libraries\excel\import;
use app\libraries\tags\collection\TagCollection;

class ImportUnit {


    private $value = null;
    private $x = null;
    private $y = null;
    private $tags = null;


    /**
     * @param TagCollection $tags
     * @param integer $X
     * @param integer $Y
     * @param string $value
     */
    function __construct($tags, $X, $Y, $value)
    {
        $this->value = $value;
        $this->tags = $tags;
        $this->x = $X;
        $this->y = $Y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return TagCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }


}