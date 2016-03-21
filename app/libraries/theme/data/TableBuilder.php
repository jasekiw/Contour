<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/2/2015
 * Time: 11:40 AM
 */

namespace app\libraries\theme\data;


use app\libraries\datablocks\DataBlock;
use app\libraries\helpers\TimeTracker;
use app\libraries\memory\MemoryDataManager;
use app\libraries\tags\DataTag;
use app\libraries\Timer\Timer;

/**
 * Class TableBuilder
 * @package app\libraries\theme\data
 */
class TableBuilder
{
    private $rows;
    private $columns;
    private $datablocks;
    private $id;
    private $readOnly = false;
    private $name;
    private $calculate = false;

    /**
     * Creates a new instance of the table builder
     * @param DataTag[] $rows
     * @param DataTag[] $columns
     * @param DataBlock[] $datablocks
     * @param String $id The id to assign to the table
     * @param null $name
     */
    public function __construct($rows, $columns, $datablocks, $id, $name)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->datablocks = $datablocks;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Gets the table html
     * @param bool $calculate
     * @return string
     */
    public function printTable($calculate = false)
    {
        if($calculate)
            MemoryDataManager::getInstance();
//        $timer = new TimeTracker();
//        $timer->startTimer("print");
        $this->calculate = $calculate;
        $nl = "\r\n";
        $response = "";
        $response .= '<table class="sheet_editor" sheet="' . $this->id . '" name="' . $this->name . '">' . $nl;

        $response.= '<thead>' . $nl;
        $response.= '<tr>' . $nl;
        $datablockIndex = 0;
        $y = 0;
        array_unshift($this->rows, null); //compensates for the headers
        $columnSize = sizeOf($this->columns);
        foreach($this->rows as $row)
        {
            $x = 0;

            foreach($this->columns as $index => $column)
            {
                if($x === 0 && $y === 0) // output blank (top left hand corner)
                    $response .= "<th></th>" . $nl . $this->getHeaderHtml($column) . $nl;
                else if($y === 0) // column outputs
                {
                    $response .= $this->getHeaderHtml($column) . $nl;
                    if($index === ($columnSize - 1))
                        $response.= '</tr></thead>' . $nl;
                }
                else if($x === 0) // rows to print, columns names
                {
                    $openRow = '<tr class="sheet_row" tag="' . $row->get_id() . '">';
                    if($y === 1)
                        $response .= $openRow . $nl;
                    else
                        $response .= '</tr>' . $openRow . $nl;
                    $response .= $this->getRowHtml($row) . $nl;
                    $response .= $this->getDataBlockHtml($this->datablocks[$datablockIndex]) . $nl;
                    $datablockIndex++;
                }
                else // datablock output
                {
                    $response .= $this->getDataBlockHtml($this->datablocks[$datablockIndex]) . $nl;
                    $datablockIndex++;
                }
                $x++;
            }
            $y++;
        }
        $response .= '</table>';
//        $timer->stopTimer("print");
//        $timer->getResults();
        return $response;
    }


    /**
     * Gets the html to output a datatag
     * @param DataTag $tag
     * @return string
     */
    private function getTagHtml($tag)
    {
        return '<td class="column_name" >' . $tag->get_name() . "</td>";
    }
    /**
     * Gets the html to output a row datagtag
     * @param DataTag $tag
     * @return string
     */
    private function getRowHtml($tag)
    {
        return '<td class="row_name" tag_id="' . $tag->get_id() . '" >' . $tag->get_name() . "</td>";
    }

    /**
     * Gets the html to output a datatag
     * @param DataTag $tag
     * @return string
     */
    private function getHeaderHtml($tag)
    {
        return '<th class="sheet_column" tag="'. $tag->get_id() . '" >' . $tag->get_name() . "</th>";
    }

    /**
     * Gets the datablock html
     * @param DataBlock $dataBlock
     * @return string
     */
    private function getDataBlockHtml($dataBlock)
    {
        $openTd = '<td class="cell">';
        $closeTd = '</td>';
        if(isset($dataBlock))
        {
            if(!$this->readOnly)
                return $openTd . '<input type="text" class="form-control input-sm" datablock="' .
                $dataBlock->get_id() . '" value="' . $this->getDataBlockValue($dataBlock) . '">' . $closeTd;
            else
                return $openTd . $this->getDataBlockValue($dataBlock) . $closeTd;
        }
        if(!$this->readOnly)
            return $openTd .'<input type="text" class="form-control input-sm" value="">' . $closeTd;
        else
            return $openTd . $closeTd;
    }

    /**
     * @param DataBlock $datablock
     * @return string
     */
    private function getDataBlockValue($datablock)
    {
//        $timer = new TimeTracker();
//        $timer->startTimer('processValue');
        $value = $this->calculate ? $datablock->getProccessedValue(true) : $datablock->getValue();
//        $timer->stopTimer('processValue');
//        $timer->getResults();

        return $value;
    }


    /**
     * Sets the read only property
     * @param bool $bool
     */
    public function setReadOnly($bool)
    {
        $this->readOnly = $bool;
    }

    /**
     * Gets the readOnly property
     * @return bool
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }
    public function getName()
    {
        return $this->name;
    }

}