<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/7/2015
 * Time: 1:45 PM
 */

namespace app\libraries\excel\templates\imports\tags;

use app\libraries\datablocks\DataBlock;
use app\libraries\excel\templates\imports\ImportUnit;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;

/**
 * Class ImportSheet. Used to import a sheet using the given ImportTemplate
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportSheet {

	/**
	 * @var DataTag[]
	 * @access private
	 */
	private $column_titles = array();
	/**
	 * @var DataTag[]
	 * @access private
	 */
	private $row_titles = array();

	private $cells_to_add = array();
	/**
	 * @var string
	 * @access private
	 */
    private $sheet_title = "";
	/**
	 * @var DataTag
	 * @access private
	 */
	private $sheetTag = null;
	/**
	 * @var ImportTemplate
	 * @access private
	 */
	private $template = null;
	/**
	 * @var int
	 * @access private
	 */
	private $currentx = 0;
	/**
	 * @var int
	 * @access private
	 */
	private $currenty = 0;

	/**
	 * @param \Maatwebsite\Excel\Collections\ $sheet
	 * @param String $sheet_title
	 * @param ImportTemplate $template
	 * @param integer $sheetnum
	 * @param DataTag $parent
	 * @throws \TijsVerkoyen\CssToInlineStyles\Exception
	 */
	public function runImport($sheet, $sheet_title, $template, $sheetnum, $parent)
	{
        $this->sheet_title = $sheet_title;
		$this->template = $template;


		if(DataTags::exists($this->sheet_title, $parent->get_id()))
		{
			$this->sheetTag = DataTags::get_by_string($this->sheet_title, $parent->get_id());
		}
		else
		{
			$this->sheetTag = new DataTag($this->sheet_title,  $parent->get_id(), Types::get_type_sheet(), $sheetnum);
			$this->sheetTag->set_sort_number($sheetnum);
			$this->sheetTag->create();
		}

		$row_number = 1;
		/** @noinspection PhpUndefinedMethodInspection */
		$sheet->each(function($row)  use (&$row_number){

			$cell_number = 1;
			/** @noinspection PhpUndefinedMethodInspection */
			$row->each(function($cell) use (&$cell_number, &$row_number)
			{
				$this->processCell($cell, $cell_number, $row_number);
				$cell_number++;
			});
			$row_number++;

		});
		$this->tagCells();
	}

	/**
	 * Runs an import on a cell. it will either make a tag out of it or add it to be a datablock.
	 * @param String $cell
	 * @param integer $cell_number
	 * @param integer $row_number
     */
	function processCell($cell, $cell_number, $row_number)
	{


		/**
		 * If in exluded area then break out of the function
		 */
		if($this->template->getRules()->inExludedArea($cell_number, $row_number)) // if the cell is in an excluded area, skip it
		{
			return;
		}
		/**
		 * if the cell isn't set, it isn't worth wasting time with the rest of the function
		 */
		if(!isset($cell))
		{
			return;
		}


		/**
		 * If in any rules and the cell actually has a value then continue
		 */
		if($this->template->getRules()->InAnyRules($cell_number, $row_number) && isset($cell))
		{
			/**
			 * Gets the rule that applies to the coords
			 */
			$rule = $this->template->getRules()->getRuleIn($cell_number, $row_number);

			if(isset($rule)) //makes sure that this is the rule that is being used
			{
				if ($rule->getFunction()->getName() == ImportRuleFunction::getChildOf()->getName()) //children function
				{
					if ($rule->getType()->getName() == Types::get_type_row()->getName()) //row
					{
						/**
						 * if the parent object is not found, throw an error
						 */
						if (!isset($this->row_titles[$rule->getParent()->getY()])) {
							\Kint::dump($this);
							echo "<br/>Canot find parent: current X: " . $cell_number . "    Y: " . $row_number . "<br/>";

						}
						$parentTag = $this->row_titles[$rule->getParent()->getY()];
						$tag = new DataTag();
						$tag->set_name($cell );
						$tag->set_parent_id($parentTag->get_id());
						$tag->set_sort_number($row_number);
						$tag->set_type(Types::get_type_row());
						$tag->create(); //adds the tag into the database
						$this->row_titles[$row_number] = $tag;
					}
					else if ($rule->getType()->getName() == Types::get_type_column()->getName()) //column
					{
						$parentTag = $this->column_titles[$rule->getParent()->getX()];
						$tag = new DataTag();
						$tag->set_name($cell );
						$tag->set_parent_id($parentTag->get_id());
						$tag->set_sort_number($cell_number);
						$tag->set_type(Types::get_type_column());
						$tag->create(); //adds the tag into the database
						$this->column_titles[$cell_number] = $tag;
					}
				} // childrenOf function
				else if ($rule->getFunction()->getName() == ImportRuleFunction::getHeaderTagFunction()->getName()) // header tags
				{

					if ($rule->getType()->getName() == Types::get_type_column()->getName()) { //column

						$tag = new DataTag($cell, $this->sheetTag->get_id(), Types::get_type_column(), $cell_number);
						$tag->create();
						$this->column_titles[$cell_number] = $tag;

					}
					else if ($rule->getType()->getName() == Types::get_type_row()->getName()) { //row
						$tag = new DataTag($cell, $this->sheetTag->get_id(), Types::get_type_row(), $row_number);
						$tag->create();
						$this->row_titles[$row_number] = $tag;
					}

				} // header tag import
			} // isset($rule)

		}
		else // add the cell as a datablock
		{
			$cell_to_add = array($cell, $cell_number,$row_number );
			array_push($this->cells_to_add, $cell_to_add);
		}
	} // function processCell



    /**
	 * Adds tags to the datablocks then adds them to the database
	 * @access private
     */
    private function tagCells()
	{
		foreach($this->cells_to_add as $cell)
		{
			$cell_value = $cell[0];
			$cell_x = $cell[1];
			$cell_y = $cell[2];
			$this->currentx = $cell[1];
			$this->currenty = $cell[2];
			if(!isset($this->column_titles[$cell_x]))
			{
				\Kint::dump($this);
			}
			$cell_column_tag = $this->column_titles[$cell_x];
			if(!isset($this->row_titles[$cell_y]))
			{
				\Kint::dump($this);
				echo "cell_x: " . $cell_x . "     cellY: " . $cell_y;

			}
			$cell_row_tag = $this->row_titles[$cell_y];

			$newcell =
				new ImportUnit(
					new TagCollection(
						array(
							$cell_row_tag, //then the row tag.
							$cell_column_tag // then the column tag.
						)
					),
					$cell_x,
					$cell_y,
					$cell_value
				);
			$datablock = new DataBlock($newcell->getTags(),Types::get_type_cell() );
			$datablock->set_value($newcell->getValue());
			$datablock->create();

		} //foreach cells to add
	}

} 