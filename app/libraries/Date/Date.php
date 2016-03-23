<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/13/2015
 * Time: 10:51 PM
 */




class Date
{
    public static function timestamp()
    {
        $date = new DateTime();
        return $date->format('Y-m-d h:i:s');
    }
    public static function getDateTimeObject($datestring)
    {
        $date = new DateTime($datestring);
        return $date;
    }

    /**
     * @param DateTime $datetime
     * @return string
     */
    public static function get_timestamp_from_object($datetime)
    {
        return $datetime->format('Y-m-d h:i:s');
    }
    public static function get_time_dif($stringdate)
    {
        $date = new DateTime($stringdate);
        $now = new DateTime();
        $difference = $now->diff($date);
        $output = "";

        if($difference->y > 0)
        {
            if($difference->y == 1)
                $output .= $difference->format('%y year ');
            else
                $output .= $difference->format('%y years ');
        }
        if($difference->m > 0)
        {
            if($difference->m == 1)
                $output .= $difference->format('%m month ');
            else
                $output .= $difference->format('%m months ');
        }
        if($difference->d > 0)
        {
            if($difference->d == 1)
                $output .= $difference->format('%d day ');
            else
                $output .= $difference->format('%d days ');
        }

        if($difference->h - 12 > 0)
        {
            if($difference->h == 1)
                $output .= $difference->format('%h hour ');
            else
                $output .= $difference->format('%h hours ');
        }
        if($difference->i > 0)
        {
            if($difference->i == 1)
                $output .= $difference->format('%i minute ');
            else
                $output .= $difference->format('%i minutes ');
        }

        if($difference->s == 1)
            $output .= $difference->format('%s second ');
        else
            $output .= $difference->format('%s seconds ');

       // $space_to_be_replace = strrpos($output, " ", 3);
        $output = self::replaceWith($output, "and", 3);
       // $output = substr_replace($output, " and ", $space_to_be_replace, 1);

        return $output . " ago";

    }
    private static function replaceWith($haystack, $replacement, $numfromEnd)
    {
        $output = explode(' ',$haystack);
        for($i = sizeof($output) - 1; $i >= 0; $i--)
            if(sizeof($output) > 4)
                if ($i == (sizeof($output) - $numfromEnd))
                    $output[$i] = $replacement . " " . $output[$i];
        $realoutput = "";
        foreach($output as $word)
            $realoutput.= $word . " ";
        $realoutput = rtrim($realoutput);
        return $realoutput;
    }
}