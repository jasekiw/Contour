<?php

namespace App\Http\Controllers;


use app\libraries\excel\import\Importer;
use app\libraries\excel\import\suite\TemplateSuiteManager;
use app\libraries\contour\Contour;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class ExcelImporterController
 * @package App\Http\Controllers
 */
class ExcelImporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        Contour::getThemeManager()->enqueueScript('MainJavascript', 'assets/ts/main/compiled.js');
        $view = \View::make('excelimporter.index');
        $suiteManager = new TemplateSuiteManager();
        $suites = $suiteManager->getSuiteCollection()->getAll();
        /** @noinspection PhpUndefinedFieldInspection */
        $view->title = "Excel Importer";
        /** @noinspection PhpUndefinedFieldInspection */
        $view->suites = $suites;
        return $this->render($view);
    }
    public function start()
    {
        $ds = DIRECTORY_SEPARATOR;
        $suiteManager = new TemplateSuiteManager();
        $suiteName = \Input::get("suite");
        $suite = $suiteManager->getSuiteCollection()->get($suiteName);
        $importTagLocation = \Input::get("importLocation");
        Contour::getConfigManager()->turnOnErrorReporting();
        $importer = new Importer();
        $importLocation = public_path("uploads{$ds}import{$ds}import.xls");
        $message = $importer->run($suite, $importLocation, $importTagLocation);
        echo $message;
        
    }

    /**
     * POST to upload
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        /**
         * @var \Symfony\Component\HttpFoundation\File\UploadedFile[]
         */
        try{
            $ds = DIRECTORY_SEPARATOR;
            $file = $request->files->get("excelFile");
            $uploadsFolder = public_path("uploads{$ds}import");
            $file->move($uploadsFolder, "import.xls");
            $response = new \stdClass();
            $response->success = true;
            $response->message = "sucessfully uploaded File";
            return json_encode($response);
        }
        catch(\Exception $e)
        {
            $response = new \stdClass();
            $response->success = false;
            $response->message = "failed to upload file";
            return json_encode($response);
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
