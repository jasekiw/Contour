<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


\Contour::construct();
/**
 * Home Route
 */
Route::get('/',array('as' => 'home', 'middleware' => 'auth', 'uses' => 'HomeController@index' ));

/**
 * Installation
 */
Route::get('install', 'InstallController@index');
Route::post('install', 'InstallController@store');


/**
 * Login and registration
 */
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
// Registration routes...
Route::get('users/register', 'Auth\AuthController@getRegister');
Route::post('users/register', 'Auth\AuthController@postRegister');

/**
 * Examples of usages of routes
 */
//Route::get('authors',array('as' => 'authors','uses' => 'AuthorsController@get_index'));
//Route::get('author/{id}',array('as' => 'author','uses' => 'AuthorsController@get_view'));
//Route::get('authors/new',array('as' => 'new_author','uses' => 'AuthorsController@get_new'));
//Route::post('authors/create', array('before' => 'csrf', 'uses' => 'AuthorsController@post_create'));
//Route::get('author/{id}/edit', array('as' => 'edit_author', 'uses' => 'AuthorsController@get_edit'));
//Route::put('author/update', array('before' => 'csrf', 'uses' => 'AuthorsController@put_update'));
//Route::delete('author/delete', array('before' => 'csrf', 'uses' => 'AuthorsController@delete_destroy'));
//Route::resource('excel', 'ExcelController');

Route::get('sandbox',array('as' => 'sandbox','middleware' => 'auth', 'uses' => 'SandboxController@index'));
//Route::get( Theme::get_ajax_manager()->get_url() . '/{id}',array('as' => 'get_ajax','middleware' => 'auth', 'uses' => 'AjaxController@get'))->where('id', '(.*)');
//Route::post( Theme::get_ajax_manager()->get_url() . '/{id}',array('as' => 'post_ajax','middleware' => 'auth', 'uses' => 'AjaxController@post'))->where('id', '(.*)');


/**
 * Excel Handlers
 */
Route::get('excel/edit/{id}',array('as' => 'edit_excel','middleware' => 'auth', 'uses' => 'ExcelController@edit'));
Route::get('excel/show/{id}',array('as' => 'edit_excel','middleware' => 'auth', 'uses' => 'ExcelController@show'));
Route::get('excel/import',array('as' => 'import_excel','middleware' => 'auth', 'uses' => 'ExcelController@store'));
Route::get('excel/convert',array('as' => 'convert_excel','middleware' => 'auth', 'uses' => 'ExcelController@convert'));
Route::resource('import', 'ExcelImporterController', [ 'names' => Contour::getRoutesManager()->getResourceRoutesForNameHelper('excelimport') ]);
Route::post('import/upload', ['as' => 'excelimport.upload','middleware' => 'auth', 'uses' => 'ExcelImporterController@upload']);
Route::post('import/start', ['as' => 'excelimport.start','middleware' => 'auth', 'uses' => 'ExcelImporterController@start']);
/**
 * Ajax Excel Handlers
 */
Route::get('ajax_excel_status',array('as' => 'status_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@status'));
Route::get('ajax_excel_reset',array('as' => 'reset_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@reset'));
Route::get('ajax_excel/{id}',array('as' => 'get_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@get'));

/**
 * Tags
 */

Route::get('tags',['as' => 'tag_index','middleware' => 'auth', 'uses' => 'TagController@index']);
Route::post('tags/move',['as' => 'tag_move','middleware' => 'auth', 'uses' => 'TagController@move']);
Route::post('tags/create',array('as' => 'tag_create','middleware' => 'auth', 'uses' => 'TagController@create'));
Route::post('tags/delete',array('as' => 'tag_delete','middleware' => 'auth', 'uses' => 'TagController@destroy'));
Route::get('tags/types',array('as' => 'tag_type','middleware' => 'auth', 'uses' => 'TagController@getTypes'));
Route::get('tags/{id}',array('as' => 'tag_show','middleware' => 'auth', 'uses' => 'TagController@show'));

/**
 * Ajax Datablock routes, will be removed in the future and replace with api routes
 */
Route::post('ajaxdatablocks/get_multiple_by_tags',['as' => 'multiple_ajax_datablock','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@get_multiple_by_tags']);
Route::get('ajaxdatablocks/{id}',['as' => 'index_ajax_datablock','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@show']);
Route::get('ajaxdatablocks/{id}',['as' => 'index_ajax_datablock','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@show']);

/**
 * API ROUTES
 */
    /**
     *  Tags subsection
     */
        Route::get('api/tags/get_children/{id}',['as' => 'api_tags_get_children','middleware' => 'auth', 'uses' => 'api\ApiTagController@get_children']);
        Route::get('api/tags/get_children_recursive/{id}',['as' => 'api_tags_get_children_recursive','middleware' => 'auth', 'uses' => 'api\ApiTagController@get_children_recursive']);
        Route::post('api/tags/getParentTrace',['as' => 'api_tag_getParentTrace','middleware' => 'auth', 'uses' => 'api\ApiTagController@getParentTrace']);

    /**
     *  Datablocks subsection
     */
        Route::post('api/datablocks/get_by_tags',['as' => 'api_datablocks_get_by_tags','middleware' => 'auth', 'uses' => 'api\ApiDataBlockController@getByTagIds']);
        Route::post('api/getValue',['as' => 'api_getValue','middleware' => 'auth', 'uses' => 'DataBlockTranslatorController@getValue']);
        Route::post('api/datablocks/save/{id}',['as' => 'api_datablocks_save','middleware' => 'auth', 'uses' => 'api\ApiDataBlockController@save']);
/**
 * END API ROUTES
 */
/**
 * Testing Datablock translating
 */
Route::get('testDataBlocktranslator', ['as' => 'api_datablocks_test','middleware' => 'auth', 'uses' => 'DataBlockTranslatorController@getValueTest']);


/**
 * Test
 */
Route::get('test', ['as' => 'test_index','middleware' => 'auth', 'uses' => 'TestController@index']);

/**
 * Math Testing
 */
Route::resource('math', 'MathController');

/**
 * User Management
 */
Route::get('users/index/{letter}', ['as' => 'users_index_letter','middleware' => 'auth', 'uses' => 'UserController@index']);
Route::get('users',['as' => 'users_index', 'middleware' => 'auth', 'uses' => 'UserController@index']);
Route::get('users/create',['as' => 'users_create', 'middleware' => 'auth', 'uses' => 'UserController@create']);
Route::post('users/create',['as' => 'users.store', 'middleware' => 'auth', 'uses' => 'UserController@store']);
Route::get('users/{id}',['as' => 'users_show', 'middleware' => 'auth', 'uses' => 'UserController@show']);
Route::put('users/{id}',['as' => 'users_save', 'middleware' => 'auth', 'uses' => 'UserController@update']);



/**
 * Profile Pages
 */
Route::get('profile',array('as' => 'view_profile', 'middleware' => 'auth', 'uses' => 'ProfileController@show'));
Route::post('profile/save',array('as' => 'save_profile','middleware' => 'auth', 'uses' => 'ProfileController@save'));
Route::post('profile/savepassword',array('as' => 'savepassword_profile','middleware' => 'auth', 'uses' => 'ProfileController@savePassword'));

/**
 * User Access Groups
 */
Route::get('groups/index/{letter}',array('as' => 'user_access_groups_index_letter', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@index'));
Route::get('groups',array('as' => 'user_access_groups_index', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@index'));
Route::get('groups/create',array('as' => 'user_access_groups_create', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@create'));
Route::post('groups/create',array('as' => 'user_access_groups_create', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@store'));
Route::get('groups/{id}',array('as' => 'user_access_groups_show', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@show'));
Route::put('groups/{id}',array('as' => 'user_access_groups_save', 'middleware' => 'auth', 'uses' => 'UserAccessGroupsController@update'));
Route::resource('testgroups','UserAccessGroupsController',  ['middleware' => 'auth',
    'names' => Contour::getRoutesManager()->getResourceRoutesForNameHelper('usergroups')
] );
/**
 * Config Routes
 */
Route::get('config',array('as' => 'view_config','middleware' => 'auth', 'uses' => 'ConfigController@show'));
Route::post('config',array('as' => 'save_congfig','middleware' => 'auth', 'uses' => 'ConfigController@save'));

/**
 * Unit Testing
 */
Route::get('unittest',array('as' => 'unit_test','middleware' => 'auth', 'uses' => 'UnitTestController@index'));

/**
 * Facilities
 */
Route::get('facilities',array('as' => 'facilities','middleware' => 'auth', 'uses' => 'FacilityController@index'));
Route::get('facilities/letter/{id}',array('as' => 'letter_facilities','middleware' => 'auth', 'uses' => 'FacilityController@index'));
Route::get('facilities/{id}',array('as' => 'get_facility','middleware' => 'auth', 'uses' => 'FacilityController@edit'));
Route::get('facilities/edit/{id}',array('as' => 'facility_edit','middleware' => 'auth', 'uses' => 'FacilityController@edit'));
Route::get('exfacilities/{id}',array('as' => 'get_ex_facility','middleware' => 'auth', 'uses' => 'FacilityController@experimentalShow'));

/**
 * Reports
 */
Route::get('reports',array('as' => 'reports','middleware' => 'auth', 'uses' => 'ReportsController@index'));
Route::get('report/edit/{id}',array('as' => 'report_edit','middleware' => 'auth', 'uses' => 'ReportsController@edit'));
Route::get('report/show/{id}',array('as' => 'report_edit','middleware' => 'auth', 'uses' => 'ReportsController@show'));

/**
 * Sheet Categories
 */
Route::get('sheetcategories',array('as' => 'sheetcategories_index','middleware' => 'auth', 'uses' => 'SheetCategoryController@index'));
Route::get('sheetcategories/index/{letter}',array('as' => 'sheetcategories_index_letter','middleware' => 'auth', 'uses' => 'SheetCategoryController@index'));
Route::get('sheetcategories/{id}',array('as' => 'sheetcategories_show','middleware' => 'auth', 'uses' => 'SheetCategoryController@show'));
Route::get('sheetcategories/{id}/{letter}',array('as' => 'sheetcategories_show_letter','middleware' => 'auth', 'uses' => 'SheetCategoryController@show'));

/**
 * Sheets
 */
Route::get('generatetemplates',array('as' => 'sheetcategories_generate','middleware' => 'auth', 'uses' => 'SheetsController@generateFacilityTemplate'));
Route::get('deletetemplates',array('as' => 'sheetcategories_delete_template','middleware' => 'auth', 'uses' => 'SheetsController@deleteGeneratedFacilityTemplate'));
Route::get('sheets/create/{id}',array('as' => 'sheet_create','middleware' => 'auth', 'uses' => 'SheetsController@create'));
Route::get('sheets/delete/{id}',array('as' => 'sheet_delete','middleware' => 'auth', 'uses' => 'SheetsController@destroy'));
Route::post('sheets/create',array('as' => 'sheet.store','middleware' => 'auth', 'uses' => 'SheetsController@store'));
Route::get('sheets/{id}',array('as' => 'sheet_edit','middleware' => 'auth', 'uses' => 'SheetsController@edit'));

/**
 * Menus
 */
Route::get('menu/index/{letter}',array('as' => 'menu_index_letter', 'middleware' => 'auth', 'uses' => 'MenuController@index'));
route::resource('menu', 'MenuController');

Route::post('menuitem',array('as' => 'create_menu_item','middleware' => 'auth', 'uses' => 'MenuItemController@store'));
Route::get('menuitem/{id}',array('as' => 'menu_item_edit','middleware' => 'auth', 'uses' => 'MenuItemController@edit'));
Route::put('menuitem/update/{id}',array('as' => 'menu_item_update','middleware' => 'auth', 'uses' => 'MenuItemController@update'));

/**
 * Jobs
 */
Route::post('jobs/start',array('as' => 'async_jobs', 'uses' => 'AsyncController@launch'));
Route::post('jobs/{id}',array('as' => 'async_jobs', 'uses' => 'AsyncController@handle'));



/**
 * Dynamic Routes. Always be routes last or else other routes will be overided
 */
//Route::get('/{id}',array('as' => 'get_dynamic', 'uses' => 'DynamicRouteController@get'))->where('id', '(.*)');
//Route::post('/{id}',array('as' => 'post_dynamic','uses' => 'DynamicRouteController@post'))->where('id', '(.*)');
\Contour::getRoutesManager()->printRoutes();