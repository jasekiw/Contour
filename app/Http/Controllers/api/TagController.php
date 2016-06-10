<?php

namespace App\Http\Controllers\api;

use app\libraries\ajax\AjaxResponse;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\TypeCategory;
use app\libraries\types\Types;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Response;
use stdClass;

/**
 * Class ApiTagController
 * @package App\Http\Controllers\api
 */
class TagController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * creates a new tag
     * POST api/tags/create
     * {
     *  name : string,
     *  parent_id : integer,
     *  type : string
     *
     * }
     * @return Response
     */
    public function create()
    {
        $reponse = new AjaxResponse();
        $name = Input::get("name");
        if(empty($name))
            return $reponse->fail("name is empty or not sent", true);
        $parent_id = Input::get("parent_id");
        if(!is_numeric($parent_id))
            return $reponse->fail("parent id is not a valid number", true);
        $type = Input::get("type");
        if(empty($type))
            return $reponse->fail("type is empty or not sent", true);
        $sort_number = Input::get("sort_number");
        

        $parent_id = intval($parent_id);
        $sort_number = intval($sort_number);
        if (!Types::exists($type))
            return $reponse->fail("That type does not exist", true);


        $type = Types::get_type_by_name($type, TypeCategory::getTagCategory());
        $tag = new DataTag($name, $parent_id, $type);
        if(is_numeric($sort_number))
            $tag->set_sort_number(intval($sort_number));
        $tag->create();
        $reponse->setPayload($tag->toStdClass());
        $reponse->success("tag successfully created");


        return $reponse->send();
    }

    public function delete()
    {
        $reponse = new AjaxResponse();
        $id = intval(Input::get("id"));
        if($id == 0)
            return $reponse->fail("not a valid id",true);
        $dataTag = DataTags::get_by_id($id);
        if (!isset($dataTag)) 
            return $reponse->fail("tag with that id not found",true);
        $dataTag->delete();
        return $reponse->success("successfully deleted", true);
    }

    /**
     * POST /api/tags/rename
     * {
     * id : number,
     * newName : string
     * }
     * @return string
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function rename()
    {
        $reponse = new AjaxResponse();
        $reponse->fail("failed");

        $newName = Input::get("newName");
        $id = intval(Input::get("id"));

        if (isset($newName) && isset($id)) {
            $dataTag = DataTags::get_by_id($id);
            if (isset($dataTag)) {
                $dataTag->set_name(DataTags::validate_name($newName));
                $dataTag->save();
                $reponse->success();
                $reponse->setPayload($dataTag->toStdClass());
            }
        }
        return $reponse->send();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function types()
    {
        $reponse = new AjaxResponse();
        $types = Types::get_all_tag_types();
        $stdTypes = [];
        foreach ($types as $key => $type)
            $stdTypes[$key] = $type->toStdClass();
        $reponse->setPayload($stdTypes);
        return $reponse->send();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tag/delete
     *
     *
     * @return Response
     */
    public function destroy()
    {
        $answer = new stdClass();
        $answer->success = false; // default
        $id = Input::get("id");
        if ($id !== null) {
            $id = intval($id);
            $tag = DataTags::get_by_id($id);
            if ($tag !== null) {
                $tag->delete_recursive();
                $answer->success = true;
            }
        }
        //$answer->id = $id;
        return json_encode($answer);
    }

    /**
     * Moves the tags to another tag
     */
    public function move()
    {
        $sources = Input::get("source");
        $reponse = new AjaxResponse();
        $targetId = intval(Input::get("target"));
        $target = DataTags::get_by_id($targetId);
        if (isset($target)) {
            foreach ($sources as $source) {
                $current = DataTags::get_by_id(intval($source));
                if ($current->get_id() != $target)
                    $current->set_parent_id($target->get_id());
                $current->save();
            }
        } else
            $reponse->fail("cannot find target");
        return $reponse->send();
    }

    /**
     * Gets the parent trace of a datablock
     * @return \Illuminate\Http\Response
     */
    public function getParentTrace()
    {
        $id = \Input::get("tag");
        $thetag = DataTags::get_by_id($id);
        $array = $thetag->getParentTrace();
        foreach ($array as $index => $tag) {
            $array[$index] = $tag->get_name();
        }
        return json_encode($array);
    }

    /**
     * GET /api/tags/children/id
     * Gets the children tags for the given tag
     * @param $id
     * @return string
     */
    public function get_children($id)
    {
        $reponse = new AjaxResponse();
        $parent = DataTags::get_by_id($id);
        if (!isset($parent)) {
            $reponse->fail("parent id not found: " . $id);
            return $reponse->send();
        }
        $children = $parent->get_children()->getAsArray();
        foreach ($children as $index => $child)
            if($child->has_children())
            {
                $child->set_type(Types::get_type_folder());
                $children[$index] = $child->toStdClass();
            }
            else
                $children[$index] = $child->toStdClass();
        $reponse->success("");
        $reponse->setPayload($children);
        return $reponse->send();
    }

    public function get($id)
    {
        $response = new AjaxResponse();
        $tag = DataTags::get_by_id($id);
        if(!isset($tag))
            $response->fail("tag not found");
        else
            $response->setPayload($tag->toStdClass());
        return $response->send();

    }
    public function getMulti()
    {
        $response = new AjaxResponse();
        $ids = Input::get('ids');
        /**
         * @var DataTag[] $tags
         */
        $tags = [];
        foreach($ids as $id)
            $tags[] = $tag = DataTags::get_by_id($id);
        $reponseTags = [];
        foreach($tags as  $tag)
            if($tag->has_children())
            {
                $tag->set_type(Types::get_type_folder());
                $reponseTags[] = $tag->toStdClass();
            }
            else
            $reponseTags[] = $tag->toStdClass();
        if(sizeof($reponseTags) == 0)
            $response->fail("tags not found");
        else
            $response->setPayload($reponseTags);
        return $response->send();
    }

    /**
     * GET /api/tags/get_children_recursive/id
     * @param $id
     * @return string
     */
    public function get_children_recursive($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if (!isset($parent)) {
            $answer->success = false;
            return json_encode($answer);
        }
        $children = $parent->get_children_recursive()->getAsArray();
        foreach ($children as $index => $child) {
            $children[$index] = [
                "id" => $child->get_id(),
                "name" => $child->get_name(),
                "sort_number" => $child->get_sort_number(),
                "type" => $child->get_type()->getName()
            ];
        }
        $answer->tags = $children;
        $answer->success = true;
        return json_encode($answer);
    }

    /**
     * @param $id
     * @return string
     */
    public function setMeta($id)
    {
        $metaKey = Input::get('metaKey');
        $metaValue = Input::get('metaValue');
        $response = new AjaxResponse();
        if($response->reliesOnMany(['metaKey' => $metaKey, 'metaValue' => $metaValue], true))
        {
            $dataTag = DataTags::get_by_id($id);
            $dataTag->setMetaValue($metaKey, $metaValue);
            $dataTag->save();
        }
        return $response->send();
    }

}
