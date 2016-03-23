<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/7/2015
 * Time: 1:45 PM
 */

namespace app\libraries\excel\import;

use app\libraries\datablocks\DataBlock;
use app\libraries\excel\import\cell\ImportCell;
use app\libraries\excel\import\exception\ParentNotFoundException;
use app\libraries\excel\import\exception\TagNotFoundException;
use app\libraries\excel\import\sheet\ImportRule;
use app\libraries\excel\import\sheet\ImportTemplate;
use app\libraries\excel\import\suite\ImportTemplateSuite;
use app\libraries\excel\Point;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Type;
use app\libraries\types\Types;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;

/**
 * Class ImportSheet. Used to import a sheet using the given ImportTemplate
 * @package app\libraries\excel\templates\imports\tags
 */
class SheetImporter {

	/**
	 * @var DataTag[]
	 * @access private
	 */
	private $column_titles = [];
	/**
	 * @var DataTag[]
	 * @access private
	 */
	private $row_titles = [];

    /**
     * @var ImportCell[]
     */
	private $cells_to_add = [];
	/**
	 * @var string
	 * @access private
	 */
    private $sheet_title = "";
	/**
	 * @var DataTag
	 * @access private
	 */
	private $sheetTag;
	/**
	 * @var ImportTemplate
	 * @access private
	 */
	private $template;
    /**
     * @var ImportTemplateSuite
     */
    private $suite;

    private $tags = [];

    /**
     * Runs the import for the given sheet in the excel workbook
     * @param RowCollection $sheet
     * @param String $sheet_title
     * @param ImportTemplate $template
     * @param integer $sheetnum
     * @param DataTag $parent
     * @param ImportTemplateSuite $suite
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
	public function runImport(RowCollection $sheet, $sheet_title, $template, $sheetnum, $parent, $suite)
	{
        $this->suite = $suite;
        $this->sheet_title = $sheet_title;
		$this->template = $template;
		if(DataTags::exists($this->sheet_title, $parent->get_id()))
			$this->sheetTag = DataTags::get_by_string($this->sheet_title, $parent->get_id());
		else
		{
			$this->sheetTag = new DataTag($this->sheet_title,  $parent->get_id(), Types::get_type_sheet(), $sheetnum);
			$this->sheetTag->set_sort_number($sheetnum);
			$this->sheetTag->create();
		}
		$row_number = 1;
		$sheet->each(function(CellCollection $row)  use (&$row_number)
        {
			$column_number = 1;
			$row->each(function($cell) use (&$column_number, &$row_number)
			{
				$this->processCell($cell, $column_number, $row_number);
				$column_number++;
			});
			$row_number++;
		});
		$this->tagCells();
	}

    /**
     * Runs an import on a cell. it will either make a tag out of it or add it to be a datablock.
     * @param string $cell
     * @param int $column_number
     * @param int $row_number
     * @throws ParentNotFoundException
     * @throws \Exception
     */
	function processCell($cell, $column_number, $row_number)
	{

        if(!isset($cell) || empty($cell)) // empty cells are not processed
            return;
		/** cells in excluded areas are not processed */
		if($this->template->getRules()->inExludedArea($column_number, $row_number))
			return;


		/** If in any rules and the cell actually has a value then continue */
		if($this->template->getRules()->InAnyRules($column_number, $row_number))
		{
			/** Gets the rule that applies to the coords */
			$rule = $this->template->getRules()->getRuleIn($column_number, $row_number);
            $rules = $this->template->getRules()->getRulesIn($column_number, $row_number);
            if(sizeof($rules) > 1)
            {
                $test = "";
                throw new \Exception("multiple rules found");
            }
            if ($rule->getFunction() == ImportRule::TAG_CHILD_OF_FUNCTION) //children function
                $this->importChildOf($rule, $cell, $column_number, $row_number);
            else if ($rule->getFunction() == ImportRule::TAG_HEADER_FUNCTION) // header tags
                $this->importHeaderTag($rule, $cell, $column_number, $row_number);
            else if($rule->getFunction() == ImportRule::TAG_PROPERTY_FUNCTION)
                $this->importHeaderTag($rule, $cell, $column_number, $row_number);
            else if($rule->getFunction() == ImportRule::CELL_TWO_DIMENSION_TAG_FUNCTION)
                array_push($this->cells_to_add, new ImportCell($cell, $column_number, $row_number, $rule)); //to be processed once we create all the tags.
            else if($rule->getFunction() == ImportRule::CELL_ONE_DIMENSION_TAG_FUNCTION)
                array_push($this->cells_to_add, new ImportCell($cell, $column_number, $row_number, $rule)); //to be processed once we create all the tags.
		}
		else // add the cell as a two dimensional cell
			array_push($this->cells_to_add, new ImportCell($cell, $column_number, $row_number,
                ImportRule::createTwoDimensionalCellRule( ( new Point($column_number,$row_number ) )->toArea() )));

	} // function processCell

    /**
     * Imports a header tag as a child of another tag
     * @param ImportRule $rule
     * @param string $cell
     * @param int $column_number
     * @param int $row_number
     * @throws ParentNotFoundException
     */
    public function importChildOf($rule, $cell, $column_number, $row_number)
    {
        /** If the parent object is not found, throw an error */
        $parentTag = $this->getTagAt($rule->getParent());
        if(!isset($parentTag))
            throw new ParentNotFoundException($column_number,$row_number, $this );
        $tag = new DataTag();
        $tag->set_name($cell );
        $tag->set_parent_id($parentTag->get_id());
        $tag->set_sort_number($row_number);
        $tag->set_type($rule->getType());
        $tag->create(); //adds the tag into the database
        $this->addTag($column_number, $row_number, $rule,$tag);
    }

    /**
     * Gets the tag at the specified point
     * @param Point $point
     * @return DataTag|null
     */
    private function getTagAt(Point $point)
    {
        if(!isset($this->tags[$point->getY()]))
            return null;
        if(!isset($this->tags[$point->getY()][$point->getX()]))
            return null;
        return $this->tags[$point->getY()][$point->getX()];
    }


    /**
     * Adds a datatag to the tracker.
     * @param int $column
     * @param int $row
     * @param ImportRule $rule
     * @param DataTag $tag
     */
    private function addTag($column, $row, $rule, $tag)
    {
        $function = $rule->getFunction();
        $typeName = $rule->getType()->getName();
        if($function == ImportRule::TAG_HEADER_FUNCTION)
        {
            if($typeName == Types::get_type_row()->getName())
                $this->row_titles[$row] = $tag;
            else if($typeName == Types::get_type_column()->getName() || $typeName ==  Types::get_type_table_header()->getName())
                $this->column_titles[$column] = $tag;
        }
        if(!isset($this->tags[$row]))
            $this->tags[$row] = [];
        $this->tags[$row][$column] = $tag;
    }
    /**
     * Imports a header tag
     * @param ImportRule $rule
     * @param string $cell
     * @param int $column_number
     * @param int $row_number
     */
    public function importHeaderTag($rule, $cell, $column_number, $row_number)
    {
        $tag = new DataTag($cell, $this->sheetTag->get_id(), $rule->getType(), $column_number);
        $tag->create();
        $this->addTag($column_number, $row_number, $rule, $tag);
    }



    /**
	 * Adds tags to the datablocks then adds them to the database
	 * @access private
     */
    private function tagCells()
	{
		foreach($this->cells_to_add as $cell)
		{
            if(!isset($cell->rule))
                continue;
            $tags = null;
            $sort_number = null;
            $type = Types::get_type_cell();
            if( $cell->rule->getFunction() == ImportRule::CELL_ONE_DIMENSION_TAG_FUNCTION )
            {

                $tag = $cell->rule->getAxis() == ImportRule::ONE_DIMENSIONS_TAG_AXIS_X
                    ? $this->row_titles[$cell->row] : $this->column_titles[$cell->column];
                $sort_number = $cell->rule->getAxis() == ImportRule::ONE_DIMENSIONS_TAG_AXIS_X ? $cell->column : $cell->row;
                $tags = new TagCollection([$tag]);
                $type = Types::get_type_table_cell();
            }
            else if($cell->rule->getFunction() == ImportRule::CELL_TWO_DIMENSION_TAG_FUNCTION )
            {
                if(!isset($this->column_titles[$cell->column]) || !isset($this->row_titles[$cell->row]))
                    throw new TagNotFoundException($cell->column, $cell->row, $this);
                $tags = new TagCollection([$this->row_titles[$cell->row], $this->column_titles[$cell->column]]);
            }
            else if($cell->rule->getFunction() == ImportRule::CELL_ONE_TAG_FUNCTION)
            {
                $tag = $this->getTagAt($cell->rule->getParent());
                if(isset($tag))
                    $tags = new TagCollection([$tag]);
            }



            if(isset($tags))
            {
                $datablock = new DataBlock($tags,$type);
                $datablock->set_value($cell->value);
                if(isset($sort_number))
                    $datablock->setSortNumber($sort_number);
                $datablock->create();
            }
            else
                throw new TagNotFoundException($cell->column, $cell->row, $this);

		} //foreach cells to add
	}

} 