<?php

namespace App\Http\Controllers;

use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SheetCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($letter = null)
    {
        $view = \View::make("excel.categories.index");
        $view->class = "list";
        $facilities = DataTags::get_by_string("excel", -1)->get_children();
        $view->newLink = "";
        $view->newTitle = "Add new Category";
        if (isset($letter)) {
            $facilities = $facilities->getTagsStartingWith($letter);
            $view->current = $letter;
            $view->letter = $letter;
        } else {
            $view->current = "all";
            $facilities = $facilities->getAsArray(TagCollection::SORT_TYPE_ALPHABETICAL);
        }

        $view->title = 'Categories';
        $view->items = $facilities;

        return $this->render($view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $letter = null)
    {
        $view = \View::make("excel.categories.index");
        $parent = DataTags::get_by_id($id);
        $excelTag = DataTags::get_by_string('excel', -1);
        $parentOfParent = $parent->get_parent();
        $trace = $parent->getParentTrace($excelTag);
        $view->class = "list";
        $breadcrumbs = [];
        array_push($breadcrumbs, ['title' => 'Categories', 'link' => route('sheetcategories_index')]);
        foreach ($trace as $tag) {
            array_push($breadcrumbs, [
                'title' => $tag->get_name(),
                'link' => route('sheetcategories_show', [$tag->get_id()])
            ]);
        }
        array_push($breadcrumbs, [
            'title' => $parent->get_name(),
            'link' => route('sheetcategories_show', [$parent->get_id()])
        ]);
        $view->breadcrumbs = $breadcrumbs;
        $categories = $parent->get_children();
        if (isset($letter)) {
            $categories = $categories->getTagsStartingWith($letter);
            $view->current = $letter;
            $view->letter = $letter;
        } else {
            $view->current = "all";
            $categories = $categories->getAsArray(TagCollection::SORT_TYPE_ALPHABETICAL);
        }

        if ($parentOfParent->get_parent() !== null) {
            $view->backToLink = route('sheetcategories_show', [$parentOfParent->get_id()]);
            $view->backToLinkTitle = $parentOfParent->get_name();
        } else {
            $view->backToLink = route('sheetcategories_index');
            $view->backToLinkTitle = "Categories";
        }

        $view->newLink = route('sheet_create', [$id]);
        $view->newTitle = "Add new Sheet";
        $view->parent = $parent;
        $view->title = $parent->get_name();
        $view->items = $categories;
        $view->tag = $parent;
        return $this->render($view);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
