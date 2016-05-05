<?php
namespace app\libraries\async;
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/17/2015
 * Time: 11:57 AM
 */
interface Async
{
    
    /**
     * Returns the name of the job
     * @return string
     */
    public static function getName();
    
    /**
     * The method that handles the job
     *
     * @param null $data
     *
     * @return mixed
     */
    public function handle($data);
    
}