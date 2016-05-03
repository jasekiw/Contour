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
    /**
     * Sets the response to fail and give a message. adds the message to the current message
     * @param string $message the message to send to the client
     */
    public function failAdditional($message = null)
    {
        $this->success = false;
        if(isset($message))
            if(strlen($this->message) == 0)
                $this->message .= $message;
            else
                $this->message .= " " . $message;
    }

    /**
     * @param string $name
     * @param mixed $var
     * @return bool successful. if false is returned then the input is not set and the operation should fail
     */
    public function reliesOn($name, $var) : bool
    {
        $this->message = "";
        if(!isset($var))
        {
            $this->failAdditional($name . " is not sent");
            return false;
        }
        else
            return true;
    }

    /**
     * @param mixed[] $arr Associative array of vairables with the names to be used that should be checked
     * @return bool successful. if false is returned then the input is not set and the operation should fail
     */
    public function reliesOnMany($arr)
    {
        foreach($arr as $name => $value)
            if(!isset($value))
            {
                $this->fail($name . " is not sent.");
                return false;
            }
        return true;
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