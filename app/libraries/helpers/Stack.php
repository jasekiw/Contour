<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 1/4/2016
 * Time: 4:22 PM
 */

namespace app\libraries\helpers;


/**
 * Class Stack
 * @package app\libraries\helpers
 */
class Stack
{
    protected $stack;

    /**
     * TokenStack constructor.
     */
    public function __construct() {
    // initialize the stack
    $this->stack = array();
}

    /**
     * @param $item
     */
    public function push($item) {
    array_unshift($this->stack, $item);
}

    /**
     * @return Object
     */
    public function pop() {
    if ($this->isEmpty())
        return null;
    else
        return array_shift($this->stack);
}

    /**
     * @return Object
     */
    public function top() {
    return current($this->stack);
}

    /**
     * @return bool
     */
    public function isEmpty() {
        return empty($this->stack);
    }
}