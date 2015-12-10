<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 6/30/2015
 * Time: 4:46 PM
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class AuthorsController extends Controller {
    public $restful = true;



    public function get_index() {
        $view = \View::make('authors.index',array('name' => 'Jason Gallavin'))->with('age', '20');
        $view->location = 'California';
        $view['specialty'] = 'PHP';
        $view->title = 'Authors and Books';
        $view->authors = author::orderBy('name', 'asc')->get();
        return $view;
    }
    public function get_view($id){
        $view = \View::make('authors.view');
        $view->title = "Author View Page";
        $view->author = author::find($id);
        return $view;
    }
    public function get_new()
    {
        $view = \View::make('authors.new');
        $view->title = 'Add New Author';
        return $view;
    }
    public function post_create()
    {
//        author::create(array(
//            'name'  =>  Input::get('name'),
//            'bio'   =>  Input::get('bio')
//        ));
        /** @var PHPExcel $excel */

        $validation = author::validate(Input::all());
        if($validation->fails())
        {
            return Redirect::route('new_author')->withErrors($validation)->withInput();
        }
        else {
            $author = new author();
            $author->name = Input::get('name');
            $author->bio = Input::get('bio');
            $author->save();
            return Redirect::route('authors')->with('message', 'The author was created successfully!');
        }
    }
    public function get_edit($id)
    {
        return \View::make('authors.edit')->with('title', 'Edit Author')
            ->with('author',author::find($id));
    }
    public function put_update()
    {
        $id = Input::get('id');
        $validation = author::validate(Input::all());
        if($validation->fails())
        {
            return Redirect::route('edit_author', $id)->withErrors($validation);
        }
        else{


            $author = author::find($id);
            $author->name = Input::get('name');
            $author->bio = Input::get('bio');
            /** @noinspection PhpUndefinedMethodInspection */
            $author->save();

            return Redirect::route('author', $id)->with('message', 'Author updated successfully!');
        }
    }

    public function delete_destroy()
    {
        author::find(Input::get('id'))->delete();
        return Redirect::route('authors')->with('message' , 'The author was deleted successfully!');
    }


}