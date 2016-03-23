<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/7/2015
 * Time: 2:48 PM
 */

namespace app\libraries\excel\templates\imports\tags;
use app\libraries\excel\Formula;
use app\libraries\excel\templates\imports\ImportCollection;
use app\libraries\tags\DataTag;
use TijsVerkoyen\CssToInlineStyles\Exception;

/**
 * Class ImportHandler: Handles the import of the Excel Document
 * @package app\libraries\excel\templates\imports\tags
 */
class ImportHandler {


    /**
     * @param \Maatwebsite\Excel\Collections\ $sheet
     * @param String $sheet_title
     * @param integer $sheet_num
     * @param TemplateCollection $template_sheets
     * @param DataTag $reports
     * @param DataTag $facilities
     * @return ImportCollection
     * @throws Exception
     */
    public function runImport($sheet, $sheet_title, $sheet_num, $template_sheets, $reports, $facilities)
	{
        gc_enable();
		if($template_sheets->exists($sheet_title))
		{
			$match = $template_sheets->find($sheet_title);
			if($match !== null)
            {
                $importer = new ImportSheet();
                /** @var ImportCollection $cells */
                $importer->runImport($sheet, $sheet_title, $match, $sheet_num, $reports);

            }
        }
        else
        {

            if($sheet_num > 9)
            {
                $match = $template_sheets->find('facility');
                $importer = new ImportSheet();
                /** @var ImportCollection $cells */
                $importer->runImport($sheet, $sheet_title, $match, $sheet_num,$facilities);
            }
            else
                throw new Exception("Sheet not found sheetnum:" . $sheet_num );
        }
	}
} 