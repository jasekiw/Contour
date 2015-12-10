<?php
namespace app\libraries\excel\formula\conversion;
use app\libraries\datablocks\DataBlock;
use app\libraries\excel\formula\TokenParsing;
use app\libraries\excel\formula\TokenParsing\FormulaParser;
use app\libraries\excel\Point;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use TijsVerkoyen\CssToInlineStyles\Exception;

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 2:21 PM
 */
class FormulaConversion{

    /**
     * @var DataTag $current_sheet
     */
    private $current_sheet = "";
    /**
     * @var DataBlock $datablock
     */
    private $datablock = null;
    /**
     * @var FormulaParser
     */
    private $parser = null;
    /**
     * @var \app\libraries\excel\formula\TokenParsing\FormulaToken
     */
    private $current_token = null;






    /**
     * Converts a datablock value to tag format
     * @param DataBlock $datablock
     * @return string
     * @throws \Exception
     */
    public function ConvertToTagFormat($datablock)
    {
        if(strContains($datablock->getValue(), '#(')) //already in tag format
            return $datablock->getValue(); // does nothing and escapes

        $this->datablock = $datablock;



        $this->current_sheet = $datablock->getTags()->getAsArray()[0]->get_a_parent_of_type(Types::get_type_sheet());
        $this->parser = new FormulaParser($datablock->getValue());


        //TODO: add $cell-value into the formula


        if($this->parser->getTokenCount() == 0)
        {
            return $datablock->getValue();
        }
        for ($i = 0; $i < $this->parser->getTokenCount(); $i++) {
            $this->current_token = $this->parser->getToken($i);
            /** @var \app\libraries\excel\formula\TokenParsing\FormulaToken current_token */
            if($this->current_token->getTokenType() == "Operand")
            {
                if($this->current_token->getTokenSubType() == "Range"){
                    $transcoded = $this->GetTagFromOperand( $this->current_token->getValue() );
                    $this->current_token->setValue( $transcoded );
                    $this->parser->setToken($this->current_token, $i);
                }
            }
        }
        $result = "";
        foreach($this->parser->getTokens() as $this->current_token)
        {
            $result .= $this->current_token->getValue();
        }
        return $result;
    }


    /**
     * @param String $operand
     * @return string
     * @throws Exception
     */
    private function GetTagFromOperand($operand)
    {
        if(strContains($operand, ":") || strContains($operand, "!") ) //the value contains more than just
        {
            if(strContains($operand, ":"))
                return $this->evaluate_range($operand);
            else
                return $this->handleCellTags($operand);
        }
        else // the value is a single cell inside the same sheet
        {
           $tags =  $this->lookupTagsByCell($operand, $this->current_sheet);
            return $tags;
        }
    }

    /**
     * converts a range of cells into tags
     * @param $range
     * @return string
     * @throws Exception
     */
    private function evaluate_range($range)
    {
        $ranges = explode(":",$range);
        if(sizeOf($ranges) != 2)
            throw new Exception("Cannot give a range with more then one colon! range: " . print_r($ranges, true));


        $left = $this->handleCellTags($ranges[0]);
        $right = $this->handleCellTags($ranges[1]);


//        $start = $ranges[0];
//        $stop = $ranges[1];
//        $converter = new ChangeBase();
//        $sheet = null;
//        if(strContains($range, "!"))
//            $sheet = $this->get_sheet($start);
//        else
//            $sheet = $this->current_sheet;


//        $start_row = (int)regex_match(/** @lang RegExp */ "/[A-Z](\d+)/",$start ); // gets the excel start row
//        $start_column = regex_match(/** @lang RegExp */"/([A-Z]+)\d/", $start); // get the excel start column
//        $start_column = $converter->getNumberValue($start_column); // gets the number value of the start column
//
//        $stop_row = (int)regex_match(/**@lang RegExp*/"/[A-Z](\d+)/",$stop );
//        $stop_column = regex_match(/** @lang RegExp */"/([A-Z]+)\d/", $stop);
//        $stop_column = $converter->getNumberValue($stop_column);

        $answer = "(";
//        for($y = $start_row; $y <= $stop_row; $y++)
//        {
//            for($x = $start_column; $x <= $stop_column; $x++)
//            {
//                try
//                {
//                    $tag = $this->lookupTagsByCell(new Point($x, $y), $sheet);
//                    if(isset($tag))
//                    {
//                        $answer .= empty($tag) ? "" :  $tag ;
//                    }
//
//                }
//                catch(\Exception $e)
//                {
//
//                }
//
//
//            }
//        }
//        if(strrpos($answer, ",") == (strlen($answer) - 1))
//        {
//            $answer = substr($answer, 0, (strlen($answer) - 1));
//        }
        $answer .= $left;
        $answer .= ":" . $right;

        $answer.= ")";
        return $answer;
       // return $left . ":" . $right;
    }



    /**
     * @param $operand
     * @return DataTag
     */
    private function get_sheet($operand)
    {
        $sheet = regex_match("/(.+?)!/", $operand);
        $sheet = str_replace(" ", "_",$sheet);
        $sheet = str_replace("'", "", $sheet);
        $sheet = DataTags::get_by_string($sheet);
        return $sheet;
    }

    /**
     * @param string $operand
     * @return string
     */
    private function remove_sheet_from_operand($operand)
    {
        return substr($operand, strpos($operand, "!") + 1,strrpos($operand, "!") - 1);
    }


    /**
     * this function assumes there are no ranges
     * @param string $location
     * @return string
     */
    private function handleCellTags($location)
    {

        if(strContains($location, "!"))
        {

            $sheet = $this->get_sheet($location);
            $tag_collection = $this->lookupTagsByCell($this->remove_sheet_from_operand($location),$sheet); // location is a string
            return $tag_collection;
        }
        else
        {
            return $this->lookupTagsByCell($location, $this->current_sheet);
        }

    }

    /**
     * This is the lowest level method, gets the #() form
     * @param String $location The row and column
     * @param DataTag $sheet The sheet to look in
     * @return string
     * @throws \Exception
     */
    private function lookupTagsByCell($location, $sheet)
    {

        $row = null;
        $column = null;
        $answer = null;
        if(is_string($location))
        {
            $converter = new ChangeBase();
            $row = regex_match(/**@lang RegExp*/"/[A-Z](\d+)/",$location );
            $column = regex_match(/**@lang RegExp*/"/([A-Z]+)\d/", $location);
            if(!isset($row) || !isset($column))
                return $location;
            // gets the column and row values
            $row = (int)$row;
            $column = $converter->getNumberValue($column);
        }
        else
        {
            $pointlocation = $location;
            $row = $pointlocation->getY();
            $column = $pointlocation->getX();
        }

        if(isset($sheet))
        {
            $rowBlock = DataTags::get_by_sort_id($row, Types::get_type_row(),$sheet->get_id());
            if(isset($rowBlock))
            {
                $columnBlock = DataTags::get_by_sort_id($column, Types::get_type_column(), $sheet->get_id());
                if(isset($columnBlock))
                {
                    $answer = new TagCollection(array($rowBlock, $columnBlock));
                }
            }
        }

        if(!isset($answer))
        {
            \Kint::dump($this);
            throw new \Exception("Tags Not Found for the value '" . $location . "'" . " with sheet '" . $sheet->get_name() . "'");
            exit;
        }

        $newvalue = "#(" ;
        $count = 0;
        $lastIndex = (sizeOf($answer->getAsArray()) - 1);
        $lastSheet = $this->current_sheet->get_name();

//        if($sheet == $this->current_sheet)
//        {
//
//        }
        foreach($answer->getAsArray() as $tag)
        {
            if($count == $lastIndex)
            {
                $newvalue .= ($sheet->get_name() == $lastSheet ? "" : ($sheet->get_name() . "/")) . $tag->get_name();
            }
            else
            {
                $newvalue .=  ($sheet->get_name() == $lastSheet ? "" : ($sheet->get_name() . "/")) . $tag->get_name() . ", ";
            }
            $lastSheet = $sheet->get_name();

                $count++;
        }
        $newvalue .= ")";
        return $newvalue;


    }


}