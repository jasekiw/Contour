<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/4/2015
 * Time: 2:40 PM
 */

namespace app\libraries\excel\templates\imports;


use app\libraries\datablocks\staticform\DataBlocks;
use \app\libraries\excel\formula\conversion\FormulaConversion;
use \app\libraries\tags\DataTag;
use \app\libraries\excel\templates\imports\tags\ImportHandler;
use \app\libraries\excel\templates\imports\Datablocks\DataBlockImporter;
use \app\libraries\excel\templates\imports\tags\RuleConstruction;
use app\libraries\types\Types;
use Maatwebsite\Excel\Facades\Excel;

class Importer
{
    public static $multithreaded = false;
    public function run()
    {

        /**
         * ImportCollection[] $all_cells
         */
        //$all_cells = array(); deprecated
        /** @noinspection PhpUndefinedClassInspection */

        $excelTag = new DataTag("excel",-1,Types::get_type_folder(),0 );
        $excelTag->create();
        $facilities = new DataTag("facilities",$excelTag->get_id(),Types::get_type_folder(),0 );
        $facilities->create();
        $reports = new DataTag("reports",$excelTag->get_id(),Types::get_type_folder(),0 );
        $reports->create();
        $template_sheets = (new RuleConstruction())->construct();

        Excel::load(storage_path() . DIRECTORY_SEPARATOR . 'excel' . DIRECTORY_SEPARATOR .'file.xls', function($reader) use (&$all_cells, &$template_sheets, &$reports, &$facilities ){

            /** @var \Maatwebsite\Excel\Readers\LaravelExcelReader $reader */
            $reader->calculate(false);
            $reader->noHeading();
            $count = 1;
            $reader->each(function($sheet)  use (&$count, $all_cells, $template_sheets, $reports, $facilities) {
                /** @var \Maatwebsite\Excel\Collections\ $sheet */
                /** @noinspection PhpUndefinedMethodInspection */
                $sheetTitle = $sheet->getTitle();
                $sheetTitle = str_replace("'", "", $sheetTitle);



                if(!Importer::$multithreaded)
                {
                    $importer = new ImportHandler();
                    $importer->runImport($sheet,$sheetTitle, $count, $template_sheets, $reports, $facilities);
                }
                else
                {
                    //TODO: add multithreaded import
                }

                echo "  " . $sheetTitle . "<br />";
                echo  "current_sheet: " . $count . "<br />";
                flush();
                \UserMeta::save('importProgressSheet', $sheetTitle);
                \UserMeta::save('importProgress', $count . "/" . "94");
                $count++;
            });

        });
        echo "DONE!";

    }

}