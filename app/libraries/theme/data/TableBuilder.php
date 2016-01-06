<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/2/2015
 * Time: 11:40 AM
 */

namespace app\libraries\theme\data;


use app\libraries\datablocks\DataBlock;
use app\libraries\tags\DataTag;

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

    /**
     * Creates a new instance of the table builder
     * @param DataTag[] $rows
     * @param DataTag[] $columns
     * @param DataBlock[] $datablocks
     * @param String $id The id to assign to the table
     * @param null $name
     */
    public function __construct($rows, $columns, $datablocks, $id, $name = null)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->datablocks = $datablocks;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Gets the table html
     * @return string
     */
    public function printTable()
    {
        $nl = "\r\n";
        $response = "";
        $response .= '<table id="' . $this->id . '">' . $nl;

        $response.= '<thead>' . $nl;
        $response.= '<tr>' . $nl;
        $datablockIndex = 0;
        $y = 0;
        array_unshift($this->rows, null);
        foreach($this->rows as $row)
        {
            $x = 0;

            foreach($this->columns as $index => $column)
            {
                if($x === 0 && $y === 0) // output blank (top left hand corner)
                {
                    $response .= "<th></th>" . $nl;
                    $response .= $this->getHeaderHtml($column) . $nl;


                }
                else if($y === 0) // column outputs
                {

                    $response .= $this->getHeaderHtml($column) . $nl;
                    if($index === (sizeOf($this->columns) - 1))
                    {
                        $response.= '</tr></thead>' . $nl;
                    }
                }
                else if($x === 0) // rows to print
                {
                    if($y === 1)
                    {
                        $response .= "<tr>" . $nl;
                    }
                    else
                    {
                        $response .= "</tr><tr>" . $nl;
                    }
                    $response .= $this->getTagHtml($row) . $nl;
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


        return $response;
    }


    /**
     * Gets the html to output a datatag
     * @param DataTag $tag
     * @return string
     */
    private function getTagHtml($tag)
    {
        return "<td>" . $tag->get_name() . "</td>";
    }

    /**
     * Gets the html to output a datatag
     * @param DataTag $tag
     * @return string
     */
    private function getHeaderHtml($tag)
    {
        return "<th>" . $tag->get_name() . "</th>";
    }

    /**
     * Gets the datablock html
     * @param DataBlock $dataBlock
     * @return string
     */
    private function getDataBlockHtml($dataBlock)
    {

        if(isset($dataBlock))
        {
            if(!$this->readOnly)
            {
                return '<td><input type="text" class="form-control input-sm" value="' . $dataBlock->getValue() . '"></td>';
            }
            else
            {
                return "<td>" . $dataBlock->getValue() . "</td>";
            }
        }
        else
        {
            if(!$this->readOnly)
            {
                return '<td><input type="text" class="form-control input-sm" value=""></td>';
            }
            else
            {
                return "<td></td>";
            }
        }
        return "";
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