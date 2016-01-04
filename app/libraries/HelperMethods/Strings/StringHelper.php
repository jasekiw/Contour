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

        try
        {
            preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE);
            if(isset($matches[1]))
            {
                $match = $matches[1];
                if(isset($match[0]))
                {
                    return $match[0];
                }
                else
                {
                    return null;
                }

            }
            else
            {
                return null;
            }

        }
        catch(\Exception $e)
        {
            return null;
        }

    }
    function regex_replace($pattern, $string)
    {

    }
}