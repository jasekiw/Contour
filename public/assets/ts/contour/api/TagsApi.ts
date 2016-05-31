import {AjaxResponse, Ajax} from "../Ajax";
import {PlainTag} from "../data/datatag/DataTag";
/**
 * Created by Jason Gallavin on 4/22/2016.
 */
export class TagsApi
{

    
    /**
     *
     * @param  name the name of the tag
     * @param  parentId the parent id that the tag will reside under
     * @param sort_number
     * @param  type the name of the type to use
     * @param  funtionToCall The function to call to give the tag object to
     */
    public static create(name : string, parentId : number, sort_number : number, type : string, funtionToCall? : (e : PlainTag) => void) : void
    {
        var data = {
            name:        name,
            parent_id:   parentId,
            type:        type,
            sort_number: sort_number
        };
        new Ajax().post("/api/tags/create",
            data
            , (e : AjaxTagReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }

    /**
     *
     * @param id
     * @param newName
     * @param funtionToCall
     */
    public static rename(id : number, newName : string, funtionToCall? : (e : PlainTag) => void)
    {
        var data = {
            id:      id,
            newName: newName
        };
        new Ajax().post("/api/tags/rename",
            data
            , (e : AjaxTagReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }

    /**
     *
     * @param id
     * @param funtionToCall
     */
    public static deleteTag(id : number, funtionToCall? : (e : AjaxResponse) => void)
    {
        var data = {
            id: id
        };
        new Ajax().post("/api/tags/delete",
            data
            , (e : AjaxTagReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e);

            });
    }

    public static setMeta(id : number, metaKey : string, metaValue : string, functionToCall? : () => void)
    {
        var data = {
            metaKey:   metaKey,
            metaValue: metaValue
        };
        new Ajax().post("/api/tags/setmeta/" + id,
            data
            , (e : AjaxTagReponse) =>
            {
                if (typeof functionToCall != "undefined")
                    functionToCall();
            });
    }

    public static getById(id : number, callback? : (e : PlainTag)=> void)
    {

        new Ajax().get("/api/tag/" + id
            , (e : AjaxTagReponse) =>
            {
                if (typeof callback != "undefined")
                    callback(e.payload);
            });
    }

    public static getByIds(ids : number[], callback? : (e : PlainTag[])=> void)
    {
        let data = {
            ids: ids
        };
        new Ajax().post("/api/tags",
            data,
            (e : AjaxTagsReponse) =>
            {
                if (typeof callback != "undefined")
                    callback(e.payload);
            });
    }

    public static getChildren(id : number, callback? : (e : PlainTag[]) => void) : void
    {
        new Ajax().get("/api/tags/children/" + id,
            (e : AjaxTagsReponse) =>
            {
                if (typeof callback != "undefined")
                    callback(e.payload);
            });
    }

    public static getChildrenRecursive(id : number, functiontoCall : (e : string) => void) : void
    {
        (new Ajax).get('/ajaxtags/get_children_recursive/' + id, functiontoCall);
    }

}

/**
 * used for Tag responses
 */
export interface AjaxTagReponse extends AjaxResponse
{
    payload : PlainTag;
}

export interface AjaxTagsReponse extends AjaxResponse
{
    payload : PlainTag[];
}