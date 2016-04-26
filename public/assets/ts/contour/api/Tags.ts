import {AjaxData, Ajax} from "../Ajax";
import {PlainTag} from "../data/datatag/PlainTag";
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
    public static create(name : string, parentId : number, type: string, funtionToCall : (e : PlainTag) => void = null) : void{
        new Ajax().post("/api/tags/create", {
            
        }, (e : AjaxTagReponse) => {
            if(e.success)
                if(funtionToCall !== null)
                    funtionToCall(e.payload);

        });
    }
}


/**
 * used for Tag responses
 */
interface AjaxTagReponse extends AjaxData{
    payload : PlainTag;
}