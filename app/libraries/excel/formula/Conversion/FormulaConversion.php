<?php
namespace app\libraries\excel\formula\conversion;

use app\libraries\datablocks\DataBlockAbstract;
use app\libraries\datablocks\DataBlock;
use app\libraries\excel\formula\TokenParsing;
use app\libraries\excel\formula\TokenParsing\FormulaParser;
use app\libraries\excel\Point;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use TijsVerkoyen\CssToInlineStyles\Exception;
use Log;

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 2:21 PM
 */
class FormulaConversion
{
    
    const EVALUATION_TYPE_SINGLE = "SINGLE";
    const EVALUATION_TYPE_RANGE = "RANGE";
    const EVALUATION_RANGE_BEGINNING = "START";
    const EVALUATION_RANGE_END = "STOP";
    /** @var DataTag $current_sheet   */
    private $current_sheet;
    /** @var DataTag $current_sheet   */
    private $current_parent;
    /** @var DataBlock $datablock   */
    private $datablock = null;
    /** @var FormulaParser   */
    private $parser = null;
    /** @var \app\libraries\excel\formula\TokenParsing\FormulaToken   */
    private $current_token = null;
    private $evaluationType = "";
    /** @var Point   */
    private $rangeStart = null;
    /** @var Point   */
    private $rangeEnd = null;
    
    /**
     * Converts a datablock value to tag format
     *
     * @param DataBlockAbstract $datablock
     *
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
        
        if (str_contains($datablock->getValue(), '#(')) //already in tag format
            return $datablock->getValue(); // does nothing and escapes
        
        $this->datablock = $datablock;
        
        $this->current_sheet = $datablock->getTags()->getAsArray()[0]->get_parent_of_type(Types::get_type_sheet());
        $this->current_parent = $datablock->getTags()->getAsArray()[0]->get_parent();
        $this->parser = new FormulaParser($datablock->getValue());
        
        //TODO: add $cell-value into the formula
        
        if ($this->parser->getTokenCount() == 0) {
            return $datablock->getValue();
        }
        for ($i = 0; $i < $this->parser->getTokenCount(); $i++) {
            $this->current_token = $this->parser->getToken($i);
            /** @var \app\libraries\excel\formula\TokenParsing\FormulaToken current_token */
            if ($this->current_token->getTokenType() == "Operand") {
                if ($this->current_token->getTokenSubType() == "Range") {
                    $transcoded = $this->GetTagFromOperand($this->current_token->getValue());
                    $this->current_token->setValue($transcoded);
                    $this->parser->setToken($this->current_token, $i);
                }
            }
        }
        $result = "";
        foreach ($this->parser->getTokens() as $this->current_token) {
            $result .= $this->current_token->getValue();
        }
        return $result;
    }
    
    /**
     * @param String $operand
     *
     * @return string
     * @throws Exception
     */
    private function GetTagFromOperand($operand)
    {
        if (strContains($operand, ":")) {
            $this->evaluationType = self::EVALUATION_TYPE_RANGE;
            return $this->evaluate_range($operand);
        }
        if (strContains($operand, "!")) {
            $this->evaluationType = self::EVALUATION_TYPE_SINGLE;
            return $this->handleCellTags($operand);
        }
        // the value is a single cell inside the same sheet
        $this->evaluationType = self::EVALUATION_TYPE_SINGLE;
        $tags = $this->lookupTagsByCell($operand, $this->current_sheet);
        return $tags;
    }
    
    /**
     * converts a range of cells into tags
     *
     * @param $range
     *
     * @return string
     * @throws Exception
     */
    private function evaluate_range($range)
    {
        $ranges = explode(":", $range);
        if (sizeof($ranges) != 2)
            throw new Exception("Cannot give a range with more then one colon! range: " . print_r($ranges, true));
        
        $left = $this->handleCellTags($ranges[0], self::EVALUATION_RANGE_BEGINNING);
        $right = $this->handleCellTags($ranges[1], self::EVALUATION_RANGE_END);
        
        $answer = "(";
        $answer .= $left;
        $answer .= ":" . $right;
        $answer .= ")";
        return $answer;
    }
    
    /**
     * this function assumes there are no ranges
     *
     * @param string $location
     * @param string $startOrFinish
     *
     * @return string
     * @throws \Exception
     */
    private function handleCellTags($location, $startOrFinish = null)
    {
        if (strContains($location, "!")) {
            $sheet = $this->get_sheet($location);
            $tag_collection = $this->lookupTagsByCell($this->remove_sheet_from_operand($location), $sheet, $startOrFinish); // location is a string
            return $tag_collection;
        }
        return $this->lookupTagsByCell($location, $this->current_sheet, $startOrFinish);
    }
    
    /**
     * @param $operand
     *
     * @return DataTag
     */
    private function get_sheet($operand)
    {
        $sheet = regex_match("/(.+?)!/", $operand);
        $sheet = DataTags::validate_name($sheet);
        $sheet = DataTags::get_by_string_and_type($sheet, Types::get_type_sheet());
        if (!isset($sheet))
            echo "STOP!";
        return $sheet;
    }
    
    /**
     * This is the lowest level method, gets the #() form
     *
     * @param String  $location The row and column
     * @param DataTag $sheet    The sheet to look in
     * @param string  $startOrFinish
     *
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
        $row = regex_match(/**@lang RegExp */
            "/[A-Z](\d+)/", $location);
        $column = regex_match(/**@lang RegExp */
            "/([A-Z]+)\d/", $location);
        if (!isset($row) || !isset($column))
            return $location;
        // gets the column and row values
        $row = (int)$row;
        $column = $converter->getNumberValue($column);
        
        $answer = $this->getTagCollectionFromLocation($row, $column, $sheet);
        
        /**
         * Location guessing, if the end of the range is whitespace we must predict where it should go
         */
        if (!isset($answer) && $this->evaluationType == self::EVALUATION_TYPE_RANGE && $startOrFinish == self::EVALUATION_RANGE_END) {
            if ($this->rangeStart->getY() - $row == 0)
                $answer = $this->getTagCollectionFromLocation($row, $column - 1, $sheet);
            else if ($this->rangeStart->getX() - $column == 0)
                $answer = $this->getTagCollectionFromLocation($row - 1, $column, $sheet);
        }
        
        if (!isset($answer)) {
            $tags = $this->datablock->getTags()->getAsArray(TagCollection::SORT_TYPE_BY_SORT_NUMBER);
            foreach ($tags as $index => $tag)
                $tags[$index] = "name: " . $tag->get_name() . ", sort_number: " . $tag->get_sort_number();
            $thesheet = $this->datablock->getTags()->getColumnsAsArray()[0]->get_parent_of_type(Types::get_type_sheet())->get_name();
            $thedatablock = print_r($tags, true);
            $thedatablock .= "\r\nSheet: " . $thesheet;
            Log::info("reference from: " . $thedatablock . "\r\nTags Not Found for the value '" . $location . "'" . " with sheet '" . $sheet->get_name() . "'");
            //throw new \Exception("Tags Not Found for the value '" . $location . "'" . " with sheet '" . $sheet->get_name() . "'");
            return $this->datablock->getValue();
        }
        
        $newvalue = "#(";
        $count = 0;
        $lastIndex = (sizeof($answer->getAsArray()) - 1);
        $lastSheet = $this->current_sheet->get_name();
        $lastParent = $this->current_parent->get_id();
        
        /**
         * set the locations of the start and stop of the current range set for guessing tags when the cell is blank
         */
        if ($startOrFinish == self::EVALUATION_RANGE_BEGINNING)
            $this->rangeStart = new Point($column, $row);
        else if ($startOrFinish == self::EVALUATION_RANGE_END)
            $this->rangeEnd = new Point($column, $row);
        
        $answer = $answer->getAsArray();
        foreach ($answer as $index => $tag) {
            if ($tag->get_type()->get_id() == Types::get_type_row()->get_id()) {
                $temp = $answer[0];
                $answer[0] = $tag;
                $answer[$index] = $temp;
            }
        }
        foreach ($answer as $tag) {
            $parent_prefix = "";
            if ($lastParent != $tag->get_parent_id()) {
                $trace = $tag->getParentTrace($sheet);
                foreach ($trace as $tracedTag)
                    $parent_prefix .= $tracedTag->get_name() . "/";
            }
            $originParentId = $this->datablock->getTags()->getRowsAsArray()[0]->get_parent_id();
            
            $sheetPrefix = $sheet->get_name() == $lastSheet ? "" : ("/" . $sheet->get_name() . "/");
            if ($sheetPrefix === "" && $parent_prefix === "" &&
                $tag->get_parent_id() == $sheet->get_id() &&
                $originParentId != $this->current_sheet->get_id() && $tag->get_parent_of_type(Types::get_type_sheet())->get_id() == $this->current_sheet->get_id()
            ) // fixes loop references & refers to this sheet
                $sheetPrefix = '&/';
            if ($count == $lastIndex)
                $newvalue .= $sheetPrefix . $parent_prefix . $tag->get_name();
            else
                $newvalue .= $sheetPrefix . $parent_prefix . $tag->get_name() . ", ";
            $lastSheet = $sheet->get_name();
            $count++;
        }
        $newvalue .= ")";
        return $newvalue;
    }
    
    /**
     * Gets the tag collection of the current row and column, if the it is not found then null is returned
     *
     * @param int     $row
     * @param int     $column
     * @param DataTag $sheet
     *
     * @return TagCollection
     */
    private function getTagCollectionFromLocation($row, $column, $sheet = null)
    {
        if (!isset($sheet))
            return null;
        $rowBlock = $sheet->findChildBySortNumber($row, Types::get_type_row());
        if (!isset($rowBlock))
            return null;
        $columnBlock = $sheet->findChildBySortNumber($column, Types::get_type_column());
        if (!isset($columnBlock))
            return null;
        return new TagCollection([$rowBlock, $columnBlock]);
    }
    
    /**
     * @param string $operand
     *
     * @return string
     */
    private function remove_sheet_from_operand($operand)
    {
        return substr($operand, strpos($operand, "!") + 1, strrpos($operand, "!") - 1);
    }
    
}