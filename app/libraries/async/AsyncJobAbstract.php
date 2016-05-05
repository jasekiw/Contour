<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/18/2015
 * Time: 10:36 AM
 */

namespace app\libraries\async;

use App\Models\Async_Job;
use Carbon\Carbon;

/**
 * Class AsyncJob
 * @package app\libraries\async
 */
abstract class AsyncJobAbstract implements Async
{
    
    public $errorMessages = null;
    protected $id;
    /** @var Async_Job   */
    protected $job;
    
    /**
     * Logs the data into a log fileS
     *
     * @param $data
     */
    public function log($data)
    {
        $DS = DIRECTORY_SEPARATOR;
        $now = Carbon::now()->toDateTimeString();
        file_put_contents(storage_path() . $DS . 'logs' . $DS . 'jobs' . $DS . static::getName() . '_' . $this->getID() . '.log',
            $now . " - " . $data . "\r\n",
            FILE_APPEND);
    }
    
    /**
     * gets the current job ID
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }
    
    /**
     * Sets the ID of the job
     *
     * @param int $id
     */
    public function setID($id)
    {
        $this->id = $id;
    }
    
    public function saveProgress($current)
    {
        if (!isset($this->job))
            $this->job = Async_Job::where('id', '=', $this->id)->first();
        $this->job->progressCurrent = $current;
        $this->job->save();
    }
    
    public function setProgressMax($total)
    {
        if (!isset($this->job))
            $this->job = Async_Job::where('id', '=', $this->id)->first();
        $this->job->progressMax = $total;
        $this->job->save();
    }
    
    /**
     * Throws and exception witht he current message
     *
     * @param string $message
     *
     * @throws \Exception
     */
    public function markErrorAndExit($message)
    {
        throw new \Exception($message);
    }
    
    /**
     * @param $message
     */
    public function markErrorAndContinue($message)
    {
        if ($this->errorMessages === null)
            $this->errorMessages = [];
        array_push($this->errorMessages, $message);
    }
    
    public function markComplete()
    {
        if (!isset($this->job))
            $this->job = Async_Job::where('id', '=', $this->id)->first();
        $this->job->complete = true;
        $this->job->save();
    }
    
    protected function turnOnErrorReporting()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
}