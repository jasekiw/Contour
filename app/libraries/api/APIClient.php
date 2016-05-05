<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/17/2015
 * Time: 8:46 AM
 */

namespace app\libraries\api;

/**
 * Class APIClient
 * @package app\libraries\api
 */
class APIClient
{
    
    public $error;
    public $successful = true;
    private $data;
    private $url;
    private $timout = 5000;
    
    /**
     * APIClient constructor.
     */
    function __construct()
    {
    }
    
    /**
     * Sets the timeout in the form of miliseconds of the postAndGetResponse() functions
     *
     * @param int $milis The miliseconds to wait
     */
    public function setTimeout($milis)
    {
        $this->timout = $milis;
    }
    
    /**
     * Sets the data to be sent.
     * expecteding a standard class that will be encoded into JSON
     *
     * @param \stdClass $data
     */
    function setData($data)
    {
        $this->data = $data;
    }
    
    function setURL($url)
    {
        $this->url = $url;
    }
    
    /**
     * Posts the data and does not wait to get the reponse
     * @requires $data has to be set first with setData()
     */
    function post_async()
    {
        if ($this->data === null) // you have to set the data first
            return;
        $post_values = json_encode($this->data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_values)
            ]
        );
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5);
        if (session_status() == PHP_SESSION_ACTIVE)
            session_write_close();
        $result = curl_exec($ch);
        curl_close($ch);
    }
    
    /**
     * Posts data and returns the data
     * @return \stdClass
     */
    function postAndGetResponse()
    {
        if ($this->data === null) // you have to set the data first
            return null;
        $post_values = json_encode($this->data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_values)
            ]
        );
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->timout);
        //$sessionStatus = session_status();
        session_write_close();
        $result = curl_exec($ch);
        if (!$result) {
            $this->error = curl_error($ch);
            $this->successful = false;
            return false;
        }
        curl_close($ch);
        return json_decode($result);
    }
    
}