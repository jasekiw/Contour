<?php
namespace App\Http\Controllers;

use app\libraries\datablocks\formula\Parser;
use app\libraries\database\DataManager;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\helpers\TimeTracker;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use Symfony\Component\Debug\Exception\FatalErrorException;

/**
 * Class DataBlockTranslatorController
 */
class DataBlockTranslatorController extends Controller
{

    /**
     * Display a listing of the resource.
     * GET /datablocktranslator
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * POST /api/getValue
     * json datablock => value
     * @return string
     */
    public function getValue()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $GLOBALS['datablockIDS'] = array();
        $value = \Input::get("datablock");
        $context = intval((\Input::get("context") !== null ? \Input::get("context") : -1));
        $datablockId = intval((\Input::get("datablockid") !== null ? \Input::get("datablockid") : -1));
        if ($datablockId !== -1) {
            $datablock = DataBlocks::getByID($datablockId);
            if (isset($datablock))
                $context = $datablock->getTags()->getRowsAsArray()[0]->get_parent_id();
        }

        $parser = new Parser(DataManager::getInstance());
        $response = new \stdClass();

        $timer = new TimeTracker();
        $timer->startTimer("parser");
        $response->result = $parser->parse($value, $context);
        $timer->stopTimer("parser");
        $response->testData = $timer->getResultsAsString();
        $response->success = true;
        return json_encode($response);
    }

    public function getValueTest()
    {

        $value = "#(Combined_OP_Summary/Revenue/Total_Revenues, Jan)-#(Phoenix_OP_Summary/Revenue/Total_Revenues, Jan)";
        $context = intval((\Input::get("context") !== null ? \Input::get("context") : 477));
        $parser = new Parser(DataManager::getInstance());
        $response = new \stdClass();
        $response->result = $parser->parse($value, $context);
        $response->success = true;
        return json_encode($response);
    }

}