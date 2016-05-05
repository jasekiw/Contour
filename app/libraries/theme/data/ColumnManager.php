<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 11/4/2015
 * Time: 11:17 PM
 */

namespace app\libraries\theme\data;

/**
 * Class ColumnManager
 * @package app\libraries\theme\data
 */
class ColumnManager
{
    
    private $numberOfColumns;
    /** @var String[][]   */
    private $columns;
    private $index = 0;
    private $before = "";
    private $after = "";
    private $hasContent = false;
    
    /**
     * Creates the column Manager. specifiy the amount of columns to use for large medium and small
     *
     * @param      $numberLarge
     * @param      $numberMedium
     * @param      $numberSmall
     * @param      $numberXSmall
     * @param null $before
     * @param null $after
     */
    public function __construct($numberLarge, $numberMedium, $numberSmall, $numberXSmall, $before = null, $after = null)
    {
        $this->numberOfColumns = $numberLarge;
        
        if (isset($before))
            $this->before = $before;
        if (isset($after))
            $this->after = $after;
        $large = 12 / $numberLarge;
        $medium = 12 / $numberMedium;
        $small = 12 / $numberSmall;
        $xSmall = 12 / $numberXSmall;
        $this->columns = [];
        for ($i = 0; $i < $numberLarge; $i++) {
            array_push($this->columns, [
                "start"   => '<div class="col-lg-' . $large .
                    ' col-md-' . $medium .
                    ' col-sm-' . $small . ' col-xs-' . $xSmall . '">',
                "end"     => "</div>",
                "content" => ""
            ]);
        }
    }
    
    public function add($html)
    {
        $this->hasContent = true;
        $this->columns[$this->index]["content"] .= $html . "\r\n";
        $this->index++;
        if ($this->index > ($this->numberOfColumns - 1)) {
            $this->index = 0;
        }
    }
    
    /**
     * Checks if anything has been added to the column manager
     * @return bool
     */
    public function hasContent()
    {
        return $this->hasContent;
    }
    
    public function getHtml()
    {
        $nl = "\r\n";
        $response = $this->before . $nl;
        foreach ($this->columns as $column) {
            $response .= $column["start"] . $nl;
            $response .= $column["content"];
            $response .= $column["end"] . $nl;
        }
        $response .= $this->after . $nl;
        return $response;
    }
    
}