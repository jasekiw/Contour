<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/21/2015
 * Time: 9:41 AM
 */

namespace app\libraries\theme\system;

use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;

/**
 * Class System
 * @package app\libraries\theme\system
 */
class System
{
    
    private static $cachedSystemTag = null;
    
    public static function get_system_tag()
    {
        if (isset($cachedSystemTag)) {
            return self::$cachedSystemTag;
        }
        $system_tag = DataTags::get_by_string("system", 0);
        if (isset($system_tag)) {
            self::$cachedSystemTag = $system_tag;
            return $system_tag;
        } else // not found. something is seriously wrong
        {
            $dataTag = new DataTag("system", 0, Types::get_type_folder());
            $dataTag->set_sort_number(1);
            $dataTag->create();
            self::$cachedSystemTag = $dataTag;
            return $dataTag;
        }
    }
    
    public static function num_cpus()
    {
        $numCpus = 1;
        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $numCpus = count($matches[0]);
        } else if ('WIN' == strtoupper(substr(PHP_OS, 0, 3))) {
            $process = @popen('wmic cpu get NumberOfCores', 'rb');
            if (false !== $process) {
                fgets($process);
                $numCpus = intval(fgets($process));
                pclose($process);
            }
        } else {
            $process = @popen('sysctl -a', 'rb');
            if (false !== $process) {
                $output = stream_get_contents($process);
                preg_match('/hw.ncpu: (\d+)/', $output, $matches);
                if ($matches) {
                    $numCpus = intval($matches[1][0]);
                }
                pclose($process);
            }
        }
        
        return $numCpus;
    }
    
}