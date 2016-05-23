import {AjaxResponse, Ajax} from "../Ajax";
import {PlainType} from "../data/type/DataType";
/**
 * Created by Jason Gallavin on 4/21/2016.
 */
export class Types
{

    /**
     *
     * @param {Function( AjaxTagArrayReponse ) } functionToCall
     */
    public static getTagTypes(functionToCall : (e : AjaxTagArrayReponse) => void)
    {
        new Ajax().get("/api/tags/types", (e : AjaxTagArrayReponse) =>
        {
            functionToCall(e);
        });
    }
}
/**
 *
 */
export interface AjaxTagArrayReponse extends AjaxResponse
{
    payload : PlainType[];
}