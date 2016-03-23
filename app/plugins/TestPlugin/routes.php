<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/7/2016
 * Time: 11:52 PM
 */

Route::get('/testplugin', ['as' => 'testplugin_index', 'uses' => function(){
   return  (new \app\plugins\TestPlugin\controllers\TestPluginController())->index();
}]);