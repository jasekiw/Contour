<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/29/2015
 * Time: 11:25 PM
 */

namespace app\libraries\datablocks\formula;

class TokenStack
{
    
    protected $stack;
    
    /**
     * TokenStack constructor.
     */
    public function __construct()
    {
        // initialize the stack
        $this->stack = [];
    }
    
    /**
     * @param $item
     */
    public function push($item)
    {
        array_unshift($this->stack, $item);
    }
    
    /**
     * @return Token
     */
    public function pop()
    {
        if ($this->isEmpty())
            return null;
        else
            return array_shift($this->stack);
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->stack);
    }
    
    /**
     * @return Token
     */
    public function top()
    {
        return current($this->stack);
    }
}