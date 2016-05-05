<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/14/2015
 * Time: 3:44 PM
 */
namespace app\libraries\Timer;
class Timer
{
    
    private $start_time = null;
    private $end_time = null;
    
    function start()
    {
        $this->start_time = $this->getmicrotime();
    }
    
    private function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    function stop()
    {
        $this->end_time = $this->getmicrotime();
    }
    
    function time()
    {
        $this->result();
    }
    
    # an alias of result function

    function result()
    {
        if (is_null($this->start_time)) {
            exit('Timer: start method not called !');
        } else if (is_null($this->end_time)) {
            exit('Timer: stop method not called !');
        }
        
        return round(($this->end_time - $this->start_time), 4);
    }
    
}