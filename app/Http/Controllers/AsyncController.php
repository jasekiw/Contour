<?php

namespace App\Http\Controllers;

use app\libraries\async\AsyncHandler;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class AsyncController
 * @package App\Http\Controllers
 */
class AsyncController extends Controller
{

    /**
     * @param $id
     * @return string
     */
    public function handle($id)
    {
        return AsyncHandler::handle($id);
    }

    public function launch()
    {
        AsyncHandler::launchIt();
    }
}
