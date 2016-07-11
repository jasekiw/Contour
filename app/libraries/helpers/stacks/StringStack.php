<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 1/4/2016
 * Time: 4:22 PM
 */

namespace app\libraries\helpers\stacks;

/**
 * Class StringStack
 * @package app\libraries\helpers
 */
class StringStack extends Stack
{
    
    protected $stack;
    
    /**
     * TokenStack constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @param string $item
     */
    public function push($item)
    {
        parent::push($item);
    }
    
    /**
     * @return String
     */
    public function pop()
    {
        return parent::pop();
    }
    
    /**
     * @return String
     */
    public function top()
    {
        return parent::top();
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return parent::isEmpty();
    }
}