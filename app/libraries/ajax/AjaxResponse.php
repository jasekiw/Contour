<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/21/2016
 * Time: 4:12 PM
 */

namespace app\libraries\ajax;


class AjaxResponse
{
    private $payload;
    private $success = true;
    private $message = "";
    function __construct()
    {
        $this->data = new \stdClass();
    }

    /**
     * Sets the data to be sent
     * @param \stdClass| \stdClass[] $data
     */
    public function setPayload( $data)
    {
        $this->payload = $data;
    }

    /**
     * Sets the response to fail and give a message
     * @param string $message the message to send to the client
     */
    public function fail($message = null)
    {
        $this->success = false;
        if(isset($message))
            $this->message = $message;
    }
    public function success($message = null) {
        $this->success = true;
        if(isset($message))
            $this->message = $message;
    }
    /**
     * Returns the json response
     * @return string
     */
    public function send()
    {
        $std = new \stdClass();
        $std->success = $this->success;
        $std->payload = $this->payload;
        $std->message = $this->message;
        return json_encode($std);
    }
}