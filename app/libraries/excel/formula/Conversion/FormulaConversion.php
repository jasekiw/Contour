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

    const EVALUATION_TYPE_SINGLE = "SINGLE";
    const EVALUATION_TYPE_RANGE = "RANGE";
    const EVALUATION_RANGE_BEGINNING = "START";
    const EVALUATION_RANGE_END = "STOP";
    private $evaluationType = "";
    /**
     * @var Point
     */
    private $rangeStart = null;
    /**
     * @var Point
     */
    private $rangeEnd = null;



    /**
     * Converts a datablock value to tag format
     * @param DataBlock $datablock
     * @return string
     * @throws \Exception
     */
    public function ConvertToTagFormat($datablock)
    {
        /**
         * Speed up debugging, remove later
         */
//            if(!str_contains($datablock->getValue(), "River''s Bend'"))
//            {
//                return $datablock->getValue();
//            }
        /**
         *
         */
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
            if (strContains($operand, ":"))
            {
                $this->evaluationType = self::EVALUATION_TYPE_RANGE;
                return $this->evaluate_range($operand);
            }
            else
            {
                $this->evaluationType = self::EVALUATION_TYPE_SINGLE;
                return $this->handleCellTags($operand);
            }

        }
        else // the value is a single cell inside the same sheet
        {
            $this->evaluationType = self::EVALUATION_TYPE_SINGLE;
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


        $left = $this->handleCellTags($ranges[0], self::EVALUATION_RANGE_BEGINNING);
        $this->rangeStart =
        $right = $this->handleCellTags($ranges[1], self::EVALUATION_RANGE_END);


        $answer = "(";
        $answer .= $left;
        $answer .= ":" . $right;
        $answer.= ")";
        return $answer;
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
        $sheet = DataTags::get_by_string_and_type($sheet, Types::get_type_sheet());
        if(!isset($sheet))
        {
            echo "STOP!";
        }
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
     * @param string $startOrFinish
     * @return string
     * @throws \Exception
     */
    private function handleCellTags($location, $startOrFinish = null)
    {

        if(strContains($location, "!"))
        {
            $sheet = $this->get_sheet($location);
            $tag_collection = $this->lookupTagsByCell($this->remove_sheet_from_operand($location),$sheet, $startOrFinish); // location is a string
            return $tag_collection;
        }
        else
        {
            return $this->lookupTagsByCell($location, $this->current_sheet, $startOrFinish);
        }

    }

    /**
     * This is the lowest level method, gets the #() form
     * @param String $location The row and column
     * @param DataTag $sheet The sheet to look in
     * @param string $startOrFinish
     * @return string
     * @throws \Exception
     */
    private function lookupTagsByCell($location, $sheet, $startOrFinish = null)
    {

        $row = null;
        $column = null;
        $answer = null;

        /**
         * Gets the row and column as integers
         */
        $converter = new ChangeBase();
        $row = regex_match(/**@lang RegExp*/"/[A-Z](\d+)/",$location );
        $column = regex_match(/**@lang RegExp*/"/([A-Z]+)\d/", $location);
        if(!isset($row) || !isset($column))
            return $location;
        // gets the column and row values
        $row = (int)$row;
        $column = $converter->getNumberValue($column);


        $answer = $this->getTagCollectionFromLocation($row, $column, $sheet);

        /**
         * Location guessing, if the end of the range is whitespace we must predict where it should go
         */
        if(!isset($answer) && $this->evaluationType == self::EVALUATION_TYPE_RANGE && $startOrFinish == self::EVALUATION_RANGE_END)
        {
            if($this->rangeStart->getY() - $row == 0)
            {
                $answer = $this->getTagCollectionFromLocation($row, $column - 1, $sheet);
            }
            else if($this->rangeStart->getX() - $column == 0)
            {
                $answer = $this->getTagCollectionFromLocation($row -1, $column, $sheet);
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

        /**
         * set the locations of the start and stop of the current range set for guessing tags when the cell is blank
         */
        if($startOrFinish == self::EVALUATION_RANGE_BEGINNING)
            $this->rangeStart = new Point($column, $row);
        else if($startOrFinish == self::EVALUATION_RANGE_END)
            $this->rangeEnd = new Point($column, $row);


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

    /**
     * Gets the tag collection of the current row and column, if the it is not found then null is returned
     * @param int $row
     * @param int $column
     * @param DataTag $sheet
     * @return TagCollection
     */
    private function getTagCollectionFromLocation($row, $column, $sheet = null)
    {
        $answer = null;
        if(isset($sheet))
        {


            $rowBlock = $sheet->findChildBySortNumber($row, Types::get_type_row());
            //$rowBlock = DataTags::get_by_sort_id($row, Types::get_type_row(), $sheet->get_id());
            if(isset($rowBlock))
            {
                //$columnBlock = DataTags::get_by_sort_id($column, Types::get_type_column(), $sheet->get_id());
                $columnBlock = $sheet->findChildBySortNumber($column, Types::get_type_column());
                if(isset($columnBlock))
                {
                    $answer = new TagCollection(array($rowBlock, $columnBlock));
                }
            }
        }
        return $answer;
    }


}