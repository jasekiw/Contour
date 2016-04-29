import {AjaxData, Ajax} from "../Ajax";
import {PlainTag} from "../data/datatag/DataTag";
/**
 * Created by Jason Gallavin on 4/22/2016.
 */
export class TagsApi {

    /**
     *
     * @param  name the name of the tag
     * @param  parentId the parent id that the tag will reside under
     * @param  type the name of the type to use
     * @param  funtionToCall The function to call to give the tag object to
     */
    public static create(name : string, parentId : number, type: string, funtionToCall? : (e : PlainTag) => void ) : void{
        var data =   {
            name: name,
            parent_id: parentId,
            type: type
        };
        new Ajax().post("/api/tags/create",
            data
            , (e : AjaxTagReponse) => {
            if(e.success)
                if(funtionToCall !== undefined)
                    funtionToCall(e.payload);

        });
    }

    /**
     *
     * @param id
     * @param newName
     * @param funtionToCall
     */
    public static rename(id : number, newName : string,  funtionToCall? : (e : PlainTag) => void)
    {
        var data = {
            id : id,
            newName: newName
        };
        new Ajax().post("/api/tags/rename",
            data
            , (e : AjaxTagReponse) => {
                if(e.success)
                    if(funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }
    /**
     *
     * @param id
     * @param funtionToCall
     */
    public static deleteTag(id : number,  funtionToCall? : (e : AjaxData) => void)
    {
        var data = {
            id : id
        };
        new Ajax().post("/api/tags/delete",
            data
            , (e : AjaxTagReponse) => {
                if(e.success)
                    if(funtionToCall !== undefined)
                        funtionToCall(e);

            });
    }

}


/**
 * used for Tag responses
 */
interface AjaxTagReponse extends AjaxData{
    payload : PlainTag;
}