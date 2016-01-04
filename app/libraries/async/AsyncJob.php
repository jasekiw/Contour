<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/18/2015
 * Time: 1:26 PM
 */

namespace app\libraries\async;


use app\libraries\api\APIClient;
use App\Models\Async_Job;

/**
 * Class AsyncManager
 * @package app\libraries\async
 */
class AsyncJob
{
    /** @var \stdClass */
    private $data;
    /** @var string */
    private $job;
    /** @var int */
    private $id;

    /**
     * AsyncJob constructor.
     */
    public function __construct()
    {

    }

    /**
     * Sets the data to be sent to the job
     * @param \stdClass $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $name
     */
    public function setJob($name)
    {
        $this->job = $name;
    }

    /**
     * Activates the job and returns the job id to track the progress
     * @requires data to be set and the job name to be set
     * @return int
     * @throws \Exception
     */
    public function activateJob()
    {
        $apiClient = new APIClient();
        $dataToSend = new \stdClass();
        $dataToSend->data = $this->data;
        $job = new Async_Job();
        $uniqueId = uniqid();
        $job->token = $uniqueId;
        $job->save();

        $dataToSend->id = $job->id;
        $dataToSend->token = $uniqueId;
        $apiClient->setData($dataToSend);
        $apiClient->setURL("http://localhost/jobs/" . $this->job);
        $apiClient->setTimeout(15000);
        $response = $apiClient->postAndGetResponse();
        if(!$apiClient->successful)
        {
            echo $apiClient->error;
            return -1;
        }

       if(!$response->success)
           throw new \Exception("unable to launch job ERROR:" . $response->error);
        $this->id = $response->id;
        return $this->id;
    }

    /**
     * Cheks the progress of the current task
     * @return string|null
     */
    public function checkProgress()
    {
        $job = Async_Job::where('id', '=', $this->id)->first();
        if($job === null)
            return null;
        return $job->progress;
    }

    /**
     * Checks if the current job is completed
     * @return bool
     */
    public function isComplete()
    {
        $job = Async_Job::where('id', '=', $this->id)->first();
        if($job === null)
            return null;
        return (boolean)$job->complete;
    }

}