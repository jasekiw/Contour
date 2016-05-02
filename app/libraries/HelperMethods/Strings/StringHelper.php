<?php

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 12/11/2015
 * Time: 4:26 PM
 */
class StringHelper
{
    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function strContains($haystack, $needle)
    {
        return strpos($haystack,$needle) > -1 ? true : false;

    }

    /**
     * @param $haystack
     * @return bool
     */
    function strContainsLetters($haystack)
    {
        return preg_match("/([A-Z])/", strtoupper($haystack)) ? true : false;
    }

    /**
     * Matches the string and returns the result
     * @param $pattern
     * @param $string
     * @return string null if not found
     */
    function regex_match($pattern, $string)
    {

        try {
            preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE);
            if(isset($matches[1]))
                return isset($matches[1][0]) ? $matches[1][0] : null;
            return null;
        }
        catch(\Throwable $e) {
            return null;
        }

    }
}