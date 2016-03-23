<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/17/2015
 * Time: 12:02 PM
 */

namespace app\libraries\async;

use app\libraries\async\jobs;
use App\Models\Async_Job;
use app\libraries\api\APIClient;
use Carbon\Carbon;


/**
 * Class AsyncHandler
 * @package app\libraries\async
 */
class AsyncHandler
{

    /**
     * @param $id
     * @return string
     */
    public static function handle($id)
    {
        return (new AsyncHandler())->handleIt($id);
    }

    /**
     * @param $id
     * @return string
     */
    public function handleIt($id)
    {
        $reponse = new \stdClass();
        $path =  dirname(__FILE__) ."/jobs";
        $files = scandir ($path);
        $classes = array();
        foreach($files as $filename)
        {
            if(!str_contains($filename, ".php"))
                continue;
            $class = str_replace(".php", "",$filename);
            $classIncludingNamespace = '\\app\\libraries\\async\\jobs\\' . $class;
            if(!class_exists($classIncludingNamespace))
                continue;
            $sublcassOf = is_subclass_of($classIncludingNamespace, 'app\libraries\async\AsyncJobAbstract');
            if(!$sublcassOf)
                continue;

            $classes[] = $classIncludingNamespace;
        }

        foreach($classes as $class)
        {
            /**  @var Async $class */
            if($class::getName() != $id)
                continue;

            $jobID = \Input::get("id");
            $uniqueToken = \Input::get('token');
            /**  @var Async|AsyncJobAbstract $handler */
            $handler = new $class();
            /** @var Async_Job $job */
            $job = Async_Job::where('id', '=', $jobID)->first();
            if($uniqueToken != $job->token)
            {
                $response = new \stdClass();
                $response->success = false;
                $response->error = "Token Mismatch";
            }
            $job->name = $handler::getName();
            $job->className = $class;
            $job->save();
            $client = new  APIClient();
            $dataToSend = new \stdClass();
            $dataToSend->id = $job->id;
            $dataToSend->data = \Input::get("data");
            $dataToSend->token = $uniqueToken;
            $client->setData($dataToSend);
            $client->setURL("http://localhost/jobs/start");
            $client->post_async();
            $reponse->error = "";
            $reponse->success = true;
            $reponse->id = $job->id;
            return json_encode($reponse);

        }
        $reponse->error = "Job not found";
        $reponse->success = false;
        return json_encode($reponse);
    }

    /**
     * Launches the Job specified
     */
    public static function launchIt()
    {
         (new AsyncHandler())->launchjob();
    }

    /**
     * Launches the job by the id
     */
    public function launchjob()
    {
        ignore_user_abort(true);
        set_time_limit(3600);
        $uniqueToken = \Input::get("token");

        $id = \Input::get("id");
        if($id === null)
            return;
        /** @var Async_Job $job */
        $job = Async_Job::where('id' , '=', $id)->first();
        if($job === null)
            return;
        if($job->token != $uniqueToken)
            return;
        $job->started = Carbon::now();
        $className = $job->className;

        /**  @var Async|AsyncJobAbstract $handler */
        $handler = new $className();
        $handler->setID($job->id);
        $job->complete = false;
        $job->save();
        try
        {
            $handler->handle(\Input::get("data"));
        }
        catch(\Exception $e)
        {
            $job->error = true;
            $handler->log($e->getMessage());
        }
        if($handler->errorMessages !== null)
        {
            $job->error = true;
            foreach($handler->errorMessages as $errorMessage)
                $handler->log($errorMessage);
        }


        $job->complete = true;
        $job->completed = Carbon::now();
        $job->save();
    }

}