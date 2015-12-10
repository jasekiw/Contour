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






//
//Route::get('/test', function()
//{
//    dd( Route::getRoutes()->getRoutes() );
//});

Route::get('/',array('as' => 'home', 'middleware' => 'auth', 'uses' => 'HomeController@index' ));
//Route::get('/',array('as' => 'home',  'uses' => 'TestController@index' ));


Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));

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


Route::get('excel/import',array('as' => 'import_excel','middleware' => 'auth', 'uses' => 'ExcelController@store'));
Route::get('excel/convert',array('as' => 'convert_excel','middleware' => 'auth', 'uses' => 'ExcelController@convert'));

Route::get('sandbox',array('as' => 'sandbox','middleware' => 'auth', 'uses' => 'SandboxController@index'));
Route::get( Theme::get_ajax_manager()->get_url() . '/{id}',array('as' => 'get_ajax','middleware' => 'auth', 'uses' => 'AjaxController@get'))->where('id', '(.*)');
Route::post( Theme::get_ajax_manager()->get_url() . '/{id}',array('as' => 'post_ajax','middleware' => 'auth', 'uses' => 'AjaxController@post'))->where('id', '(.*)');

Route::get('excel/edit/{id}',array('as' => 'edit_excel','middleware' => 'auth', 'uses' => 'ExcelController@edit'));
Route::get('ajax_excel_status',array('as' => 'status_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@status'));
Route::get('ajax_excel_reset',array('as' => 'reset_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@reset'));
Route::get('ajax_excel/{id}',array('as' => 'get_ajax_excel','middleware' => 'auth', 'uses' => 'AjaxExcelController@get'));


Route::get('tags',array('as' => 'index_tags','middleware' => 'auth', 'uses' => 'TagController@index'));
Route::post('tags/move',array('as' => 'move_tags','middleware' => 'auth', 'uses' => 'TagController@move'));
Route::post('tags/create',array('as' => 'create_tags','middleware' => 'auth', 'uses' => 'TagController@create'));
Route::post('tags/delete',array('as' => 'delete_tags','middleware' => 'auth', 'uses' => 'TagController@destroy'));
Route::get('tags/types',array('as' => 'type_tags','middleware' => 'auth', 'uses' => 'TagController@getTypes'));
Route::get('tags/{id}',array('as' => 'index_tag','middleware' => 'auth', 'uses' => 'TagController@show'));


Route::get('ajaxtags/get_children/{id}',array('as' => 'get_children_ajaxtag','middleware' => 'auth', 'uses' => 'AjaxTagController@get_children'));
Route::get('ajaxtags/get_children_recursive/{id}',array('as' => 'get_children_ajaxtag','middleware' => 'auth', 'uses' => 'AjaxTagController@get_children_recursive'));


Route::get('ajaxdatablocks/get_multiple_by_tags',array('as' => 'multiple_ajax_datablock_index','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@index'));
Route::post('ajaxdatablocks/get_multiple_by_tags',array('as' => 'multiple_ajax_datablock','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@get_multiple_by_tags'));
Route::get('ajaxdatablocks/{id}',array('as' => 'index_ajax_datablock','middleware' => 'auth', 'uses' => 'AjaxDataBlockController@show'));


Route::post('api/getValue',['as' => 'api_getValue','middleware' => 'auth', 'uses' => 'DataBlockTranslatorController@getValue']);

Route::resource('math', 'MathController');


Route::get('profile',array('as' => 'view_profile', 'middleware' => 'auth', 'uses' => 'ProfileController@show'));
Route::post('profile/save',array('as' => 'save_profile','middleware' => 'auth', 'uses' => 'ProfileController@save'));



Route::get('config',array('as' => 'view_config','middleware' => 'auth', 'uses' => 'ConfigController@show'));
Route::post('config',array('as' => 'save_congfig','middleware' => 'auth', 'uses' => 'ConfigController@save'));




Route::get('unittest',array('as' => 'unit_test','middleware' => 'auth', 'uses' => 'UnitTestController@index'));


Route::get('facilities',array('as' => 'facilities','middleware' => 'auth', 'uses' => 'FacilityController@index'));
Route::get('facilities/letter/{id}',array('as' => 'letter_facilities','middleware' => 'auth', 'uses' => 'FacilityController@index'));

Route::get('reports',array('as' => 'reports','middleware' => 'auth', 'uses' => 'ReportsController@index'));
Route::get('facilities/{id}',array('as' => 'get_facility','middleware' => 'auth', 'uses' => 'FacilityController@show'));

Route::get('menu',array('as' => 'get_menus','middleware' => 'auth', 'uses' => 'MenuController@index'));
Route::post('menu',array('as' => 'create_menu','middleware' => 'auth', 'uses' => 'MenuController@store'));

Route::get('menu/{id}',array('as' => 'get_menu','middleware' => 'auth', 'uses' => 'MenuController@show'));
Route::post('menu/{id}',array('as' => 'edit_menu','middleware' => 'auth', 'uses' => 'MenuController@edit'));
Route::delete('menu/{id}',array('as' => 'edit_menu','middleware' => 'auth', 'uses' => 'MenuController@destroy'));


Route::post('menuitem',array('as' => 'create_menu_item','middleware' => 'auth', 'uses' => 'MenuItemController@store'));




/**
 * Dynamic Routes. Always be routes last or else other routes will be overided
 */

Route::get('/{id}',array('as' => 'get_dynamic', 'uses' => 'DynamicRouteController@get'))->where('id', '(.*)');
Route::post('/{id}',array('as' => 'post_dynamic','uses' => 'DynamicRouteController@post'))->where('id', '(.*)');

//Route::get('/testphp', function() {
//    echo "test";
//});


