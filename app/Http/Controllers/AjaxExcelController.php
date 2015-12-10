<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use app\libraries\excel\templates\imports\Importer;
use \app\libraries\excel\templates\imports\Datablocks\DataBlockImporter;
class AjaxExcelController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /excel
     *
     * @return Response
     */
    public function index()
    {



    }

    /**
     * Gets a table from the database
     * GET /ajax_excel/{id}
     *
     * @param $id
     * @return Response
     */
    public function get($id)
    {

        \app\libraries\theme\data\TableConstructor::printTable($id);

    }

    public function status()
    {

        return UserMeta::get('tableLoading');

    }
    public function reset()
    {
        UserMeta::save('tableLoading', '');
    }
}