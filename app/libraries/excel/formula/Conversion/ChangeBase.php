<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/30/2015
 * Time: 4:10 PM
 */

namespace app\libraries\excel\formula\conversion;


/**
 * Class ChangeBase
 * @package app\libraries\excel\formula\conversion
 */
class ChangeBase
{

    private $transcoderToLetters = array(
        1 => "A",
        2 => "B",
        3 => "C",
        4 => "D",
        5 => "E",
        6 => "F",
        7 => "G",
        8 => "H",
        9 => "I",
        10 => "J",
        11 => "K",
        12 => "L",
        13 => "M",
        14 => "N",
        15 => "O",
        16 => "P",
        17 => "Q",
        18 => "R",
        19 => "S",
        20 => "T",
        21 => "U",
        22 => "V",
        23 => "W",
        24 => "X",
        25 => "Y",
        26 => "Z"
    );


    private  $transcoderToNumbers = array(
        "A" => 1,
        "B" => 2,
        "C" => 3,
        "D" => 4,
        "E" => 5,
        "F" => 6,
        "G" => 7,
        "H" => 8,
        "I" => 9,
        "J" => 10,
        "K" => 11,
        "L" => 12,
        "M" => 13,
        "N" => 14,
        "O" => 15,
        "P" => 16,
        "Q" => 17,
        "R" => 18,
        "S" => 19,
        "T" => 20,
        "U" => 21,
        "V" => 22,
        "W" => 23,
        "X" => 24,
        "Y" => 25,
        "Z" => 26
    );


    /**
     * @param string $letterCode
     * @return int|number
     */
    function getNumberValue($letterCode)
    {
        $letterCode = strtoupper($letterCode);
        $letters = str_split($letterCode,1);
        $total = 0;
        $maximum_size = sizeOf($letters) -1;
        $index = 0;
        $base = 26;
        foreach($letters as $letter)
        {
            $number_away = $maximum_size - $index;
            $multiplier = pow($base,$number_away);
            $total += $multiplier * $this->transcoderToNumbers[$letter];
            $index++;
        }
        return $total;
    }



    function toAlpha($data){
        $data = $data - 1;
        $alphabet =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
            return $alphabet[$data];
        }
        elseif($data > 25){
            $dividend = ($data + 1);
            $alpha = '';
            $modulo = null;
            while ($dividend > 0){
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            return $alpha;
        }
        return null;
    }



}


