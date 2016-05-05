<?php
namespace App\Http\Controllers;

use app\libraries\excel\import\suite\TemplateSuiteManager;
use app\libraries\tags\DataTags;
use App\Http\Requests;
use app\libraries\excel\import\Importer;
use app\libraries\excel\convert\datablocks\DataBlockImporter;
use Illuminate\Contracts\View\View;
use Response;
use app\libraries\theme\Theme;
use URL;

/**
 * Class ExcelController
 */
class ExcelController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /excel
     *
     * @return Response
     */
    public function index()
    {
        //return \View::make("Excel.index")->with("reader", $results);

    }

    /**
     * Show the form for creating a new resource.
     * GET /excel/convert
     *
     * @return Response
     */
    function convert()
    {
        $this->turn_on_error_reporting();
        $datablock_importer = new DataBlockImporter();
        $datablock_importer->runImport();
    }

    function turn_on_error_reporting()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    /**
     * Show the form for creating a new resource.
     * GET /excel/create
     *
     * @return Response
     */
    public function create()
    {
//		$classtag = new DataTag("EG Corporate Projections", null, "class", 0);
//		$categoryTag = new DataTag("category_name", null, "category", 25);
//		$instance_tag = newDataTag("instance_name", null, "instance", 10);
//		$classtag1 = new DataTag("Ridgewood", null, "class", 0);
//		$categoryTag1 = new DataTag("category_name1", null, "category", 26);
//		$instance_tag1 = newDataTag("instance_name1", null, "instance", 11);
//		$tagcollection = new \app\libraries\tags\collection\TagCollection($classtag,$categoryTag, $instance_tag);
//		$tagcollection1 = new \app\libraries\tags\collection\TagCollection($classtag1,$categoryTag1, $instance_tag1);
//		$all_cells = array(array("tags" => $tagcollection), array("tags" => $tagcollection1));
//
//		$current_cell = array("value" => "=Ridgewood!J25:Ridgewood!K26", "tags" => $tagcollection);
//		$converter = new FormulaConversion();
//		$result = $converter->ConvertToTagFormat($current_cell, $all_cells);
//		echo $result;
    }

    /**
     * Store a newly created resource in storage.
     * POST /excel
     *
     * @return Response
     */
    public function store()
    {
        $this->turn_on_error_reporting();
        $suiteManager = new TemplateSuiteManager();
        $suite = $suiteManager->getSuiteCollection()->get("Main Budget Import");
        $importer = new Importer();
        $ds = DIRECTORY_SEPARATOR;
        $importer->run($suite, storage_path("excel{$ds}file.xls"), "/");
    }

    /**
     * Show the form for editing the specified resource.
     * GET /excel/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
//		Theme::enqueue_script('data_block_editor', "assets/js/datablock_editor/ts/main.js");
        $datatag = DataTags::get_by_id($id);
        $view = \View::make('excel.index');
        $view->title = "Excel Editor";
        $view->subtitle = $datatag->get_name();
        $parent_id = $datatag->get_parent_id();
        $url = null;
        if ($parent_id != -1) {
            $url = URL::action('TagController@show', [$parent_id]);
        } else {
            $url = route('tag_index');
        }
        $view->backtoLink = '<a href="' . $url . '"><i class="fa fa-arrow-left back_arrow"></i>Back to Tag Browser</a>';
        $view->sheet = $datatag;
        return $this->render($view);
    }

    /**
     * @param $id
     * @return View
     */
    public function show($id)
    {
        Theme::enqueue_script('data_block_editor', "assets/js/datablock_viewer/main.js");
        $datatag = DataTags::get_by_id($id);
        $view = \View::make('excel.calculated');
        $view->title = "Excel Viewer";
        $view->subtitle = $datatag->get_name();
        $parent_id = $datatag->get_parent_id();
        $url = null;
        $view->backtoLink = '';
        $view->sheet = $datatag;
        $view->sheetId = $datatag->get_id();
        return $this->render($view);
    }

}