<?php

namespace App\Http\Controllers;

use app\libraries\theme\data\TableConstructor;
use app\libraries\user\UserMeta;
use App\Http\Requests;
use Response;

/**
 * Class AjaxExcelController
 * @package App\Http\Controllers
 */
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
        //
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

        TableConstructor::printTable($id);

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