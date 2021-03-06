<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/4/2015
 * Time: 2:40 PM
 */

namespace app\libraries\excel\import;

use app\libraries\contour\Contour;
use app\libraries\excel\import\exception\SheetNotFoundException;
use app\libraries\excel\import\suite\ImportTemplateSuite;
use \app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use app\libraries\user\UserMeta;
use Excel;
use Exception;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Collections\SheetCollection;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * This class is used to import excel sheets into the database as tags and datablocks
 * @package app\libraries\excel\import
 */
class Importer
{

    public static $multithreaded = false;
    protected $error = "";

    /**
     * Runs the import on the specified excel file with the import suite and location to import into
     *
     * @param ImportTemplateSuite $importSuite The template suite used to define the import parameters
     * @param string $fileLocation The location of the excel file.
     * @param string $tagLocation The tag location to import into.
     *
     * @return string Any response from the import
     */
    public function run($importSuite, $fileLocation, $tagLocation)
    {
        Contour::getConfigManager()->setTimeLimit(1200); //20 minutes
        $tag = $this->getTagFromPath($tagLocation);
        if (!isset($tag))
            return $this->error;
        $importSuite->setBaseTag($tag);
        $importSuite->runPreImportTasks();
        try {
            Excel::load($fileLocation, $this->loadExcel($importSuite));
            return "DONE!";
        } catch (SheetNotFoundException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Gets the excel tag in the root directory.
     * This is the root directory for importing.
     * If it does not exist, it will create it.
     * @return DataTag
     */
    public function getExceltag()
    {
        $excelTag = DataTags::get_by_string("excel", -1);
        if (!isset($excelTag)) {
            $excelTag = new DataTag("excel", -1, Types::get_type_folder(), 0);
            $excelTag->create();
        }
        return $excelTag;
    }

    /**
     * @param $tagLocation
     * @return DataTag | null
     */
    protected function getTagFromPath($tagLocation)
    {
        $excelTag = $this->getExceltag();
        $excelTagName = $excelTag->get_name();
        $path = explode("/", $tagLocation);
        foreach ($path as $key => $tag)
            if ($tag == "")
                unset($path[$key]);
        $path = array_values($path);
        $currentTag = $excelTag;
        if (sizeof($path) == 1 && $path[0] == "")
            $currentTag = $excelTag;
        else
            foreach ($path as $tag) {
                $child = $currentTag->findChild($tag);
                if (!isset($child)) {
                    $this->error = "Tag: $tag not found in file path /$excelTagName/$tagLocation";
                    return null;
                }
                $currentTag = $child;
            }
        return $currentTag;
    }

    /**
     * Loads the excel file and loops the sheets.
     *
     * @param ImportTemplateSuite $importSuite
     *
     * @return \Closure
     */
    private function loadExcel(&$importSuite)
    {
        return
            function (LaravelExcelReader $reader) use (&$importSuite) {
                $reader->calculate(false);
                $reader->noHeading();
                $count = 1;
                $reader->each($this->processSheet($count, $importSuite));
            };
    }

    /**
     * Loops through the excel sheets and imports them
     *
     * @param int $count
     * @param ImportTemplateSuite $importSuite
     *
     * @return \Closure
     */
    private function processSheet(&$count, &$importSuite)
    {
        return
            function (RowCollection $sheet) use (&$count, &$importSuite) {
                $nl = "<br />";
                $sheetTitle = str_replace("'", "", $sheet->getTitle());

                echo "Importing Sheet: " . $count . " - " . $sheetTitle . $nl;
                flush();
                if (!Importer::$multithreaded)
                    $this->runImport($sheet, $sheetTitle, $count, $importSuite);
                else {
                    /** TODO: add multithreaded import */
                }
                echo "Imported Sheet: " . $count . " - " . $sheetTitle . $nl;
                flush();
                UserMeta::save('importProgressSheet', $sheetTitle);
                UserMeta::save('importProgress', $count . "/" . "94");
                $count++;
            };
    }

    /**
     * Runs the import for the current sheet.
     * It created a new SheetImporter object and passes the suite to it.
     *
     * @param RowCollection $sheet
     * @param String $sheet_title
     * @param integer $sheet_num
     * @param ImportTemplateSuite $suite
     *
     * @return ImportCollection
     * @throws Exception
     */
    public function runImport(RowCollection $sheet, $sheet_title, $sheet_num, $suite)
    {
        $template_sheets = $suite->getTemplateCollection();
        gc_enable();
        if ($template_sheets->exists($sheet_title)) {
            $match = $template_sheets->find($sheet_title);
            $parent = $match->getParentTag();
            $importer = new SheetImporter();
            $importer->runImport($sheet, $sheet_title, $match, $sheet_num, $parent, $suite);
        } else if ($template_sheets->inRule($sheet_title)) {
            $rule = $template_sheets->getRule($sheet_title);
            $match = $template_sheets->find($rule->templateSheet);
            $parent = $match->getParentTag();
            $importer = new SheetImporter();
            $importer->runImport($sheet, $sheet_title, $match, $sheet_num, $parent, $suite);
        } else if ($template_sheets->inRule((int)$sheet_num)) {
            $rule = $template_sheets->getRule((int)$sheet_num);
            $match = $template_sheets->find($rule->templateSheet);
            $parent = $match->getParentTag();
            $importer = new SheetImporter();
            $importer->runImport($sheet, $sheet_title, $match, $sheet_num, $parent, $suite);
        } else // not found at all :(
            throw new SheetNotFoundException($sheet_num, $sheet_title);
    }

}