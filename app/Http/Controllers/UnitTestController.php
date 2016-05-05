<?php
namespace App\Http\Controllers;

use app\libraries\datablocks\staticform\DataBlocks;
use App\Http\Requests;
use app\libraries\datablocks\DataBlock;
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use Response;

/**
 * Class UnitTestController
 * @package App\Http\Controllers
 */
class UnitTestController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /unittest
     *
     * @return Response
     */
    public function index()
    {
//		Theme::getMenue()->get_menu("main_menu")->add_new("Home", "/", "",1);
//		Theme::getMenue()->save();
//		$menu = Theme::getMenue();
//		\Kint::dump($menu);
        $test = new DataBlock();
//		$testTag = new DataTag('test', 0, Types::get_type_folder(), 0);
//		$testTag->create();
        $testTag = DataTags::get_by_string('test', 0);
        $test->set_tags(new TagCollection([$testTag]));
        $test->set_value("test");
        $test->set_type(Types::get_type_cell());
        $test->create();
        $testBlock = DataBlocks::getByID(37819);
    }

    public function findDatablockByTags($datablocks, $column, $row)
    {
        $first = $column < $row ? $column : $row;
        $second = $column > $row ? $column : $row;
        foreach ($datablocks as $key => $datablock) {
            if ($datablock[0] == $first && $datablock[1] == $second) {
                return $datablock['value'];
            }
        }
        return null;
    }

    /**
     * Show the form for creating a new resource.
     * GET /unittest/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /unittest
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /unittest/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /unittest/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /unittest/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /unittest/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}