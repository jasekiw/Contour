<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 3/23/2016
 * Time: 3:08 PM
 */

namespace app\libraries\excel\import\cell;

use app\libraries\excel\import\sheet\ImportRule;

class ImportCell
{

    /**
     * @var string
     */
    public $value = "";
    /**
     * @var int
     */
    public $column = 0;
    /**
     * @var int
     */
    public $row = 0;
    /**
     * @var ImportRule
     */
    public $rule = null;

    /**
     * ImportCell constructor.
     * @param string $value
     * @param int $column
     * @param int $row
     * @param ImportRule $rule
     */
    public function __construct($value, $column, $row, $rule)
    {
        $this->value = $value;
        $this->column = $column;
        $this->row = $row;
        $this->rule = $rule;
    }
}