<?php

namespace App\Http\Controllers;

use app\libraries\excel\ExcelView;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use Illuminate\Http\Request;
use App\Http\Requests;

use Input;
use Redirect;
use View;

class SheetsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $view = View::make('sheets.create');
        $excel = DataTags::get_by_string('excel', -1);
        $templateTag = $excel->findChild('templates');
        $templateTags = $templateTag->get_children()->getAsArray(TagCollection::SORT_TYPE_BY_SORT_NUMBER);
        $templates = [];
        $templates[] = "";
        foreach ($templateTags as $template)
            $templates[$template->get_id()] = $template->get_name();
        $view->templates = $templates;
        $view->title = "Add New Sheet";
        $view->parentID = $id;
        return $this->render($view);
    }

    public function generateFacilityTemplate()
    {
        $excel = DataTags::get_by_string('excel', -1);
        $facilites = $excel->findChild('facilities');
        $firstFacility = $facilites->get_children()->getAsArray()[0];
        $templates = new DataTag('templates', $excel->get_id(), Types::get_type_folder(), 3);
        $templates->create();
        $facilityTemplate = new DataTag('facility', $templates->get_id(), Types::get_type_sheet(), 1);
        $facilityTemplate->create();
        $facilityTemplate->clone_children($firstFacility);
    }

    public function deleteGeneratedFacilityTemplate()
    {
        $excel = DataTags::get_by_string('excel', -1);
        $templates = $excel->findChild('templates');
        $templates->force_delete_recursive();
        $templates->delete();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function createFacility($id)
    {
//        Theme::enqueue_script('data_block_editor', "assets/ts/Main/compiled.js");
        $view = View::make('sheets.createfacility');
        $excel = DataTags::get_by_string('excel', -1);
        $templateTag = $excel->findChild('templates');
        $template = $templateTag->findChild("labor_rate_calculation");
        $excelView = new ExcelView($template);
        $view->laborTemplate = $excelView;
        $view->title = "Add New Facility";
        $view->parentID = $id;
        return $this->render($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parentID = Input::get('parent');
        $parent = DataTags::get_by_id($parentID);

        $name = Input::get('name');
        $template = Input::get('template');
        $templateTag = DataTags::get_by_id($template);
        $newSheet = new DataTag($name, $parentID, Types::get_type_sheet());
        $newSheet->create();
        if(isset($templateTag))
            $newSheet->clone_children($templateTag);
        return Redirect::route('sheet_edit', [$newSheet->get_id()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $view = \View::make("excel.editor"); // gets the editor view
        $excelView = new ExcelView(DataTags::get_by_id($id), false);
        $view->tag = $excelView->parentTag;
        $view->parent = $excelView->parentTag->get_parent();
        $view->title = $excelView->parentTag->get_name();
        $view->sheet = $excelView;
        return $this->render($view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = DataTags::get_by_id($id);
        $parent = $tag->get_parent_id();
        $tag->force_delete_recursive();
        return Redirect::route('sheetcategories_show', [$parent]);
    }
}
