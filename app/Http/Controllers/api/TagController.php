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
        $reponse->fail();
        $reponseData = new stdClass();
        $name = Input::get("name");
        $parent_id = Input::get("parent_id");
        $type = Input::get("type");

        if ($name !== null && $parent_id !== null && $type !== null) {
            $parent_id = intval($parent_id);
            if (Types::exists($type)) {
                $type = Types::get_type_by_name($type, TypeCategory::getTagCategory());

                $tag = new DataTag($name, $parent_id, $type);
                $tag->create();
                $reponse->setPayload($tag->toStdClass());
                $reponse->success();
            }
        }
        return $reponse->send();
    }

    public function delete()
    {
        $reponse = new AjaxResponse();
        $reponse->fail("unkown id");
        $id = intval(Input::get("id"));

        if (isset($id)) {
            $dataTag = DataTags::get_by_id($id);
            if (isset($dataTag)) {
                $dataTag->fullDelete();
                $reponse->success();
            }
        }
        return $reponse->send();
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
     * GET /api/tags/get_children/id
     * Gets the children tags for the given tag
     * @param $id
     * @return string
     */
    public function get_children($id)
    {
        $answer = new \stdClass();
        $parent = DataTags::get_by_id($id);
        if (!isset($parent)) {
            $answer->success = false;
            return json_encode($answer);
        }

        $children = $parent->get_children()->getAsArray();
        foreach ($children as $index => $child) {
            $children[$index] = array(
                "id" => $child->get_id(),
                "name" => $child->get_name(),
                "sort_number" => $child->get_sort_number(),
                "type" => $child->get_type()->getName()
            );
        }
        $answer->tags = $children;
        $answer->success = true;
        return json_encode($answer);
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
