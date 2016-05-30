import {PlainDataBlock} from "../data/datablock/DataBlock";
import {Ajax, AjaxResponse} from "../Ajax";

export class DataBlocksApi
{

    /**
     *
     * @param tagIds The tags that will be assigned
     * @param type the name of the type to use
     * @param value
     * @param sortNumber The sort number to assign to the datablock
     * @param funtionToCall The function to call to give the tag object to
     */
    public static create(tagIds : number[], type : string, value : string, sortNumber : number, funtionToCall? : (e : PlainDataBlock) => void) : void
    {
        var data = {
            tagIds:  tagIds,
            value: value,
            type:  type,
            sort_number: sortNumber
        };
        new Ajax().post("/api/datablocks/create",
            data
            ,(e : AjaxDataBlockReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }

    /**
     *
     * @param id
     * @param value
     * @param funtionToCall
     */
    public static save(id : number, value : string, funtionToCall? : (e : PlainDataBlock) => void)
    {
        var data = {
            id:    id,
            value: value
        };
        new Ajax().post("/api/datablocks/save",
            data
            ,(e : AjaxDataBlockReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }

    /**
     * removes the datablocks from the database.
     * @param ids The ids of the datablocks to remvoe
     */
    public static remove(ids : number[])
    {
        if(ids.length == 0)
            return;
        var data = {
            ids:    ids,
        };
        new Ajax().post("/api/datablocks/remove/bulk",
            data
            ,(e : AjaxResponse) => {

            });
    }


    public static getById(id : number, functiontoCall : ()=> void) : void
    {
        (new Ajax).get('/ajaxdatablocks/' + id, functiontoCall);
    }

    public static getMultipleByTags(tags : string[], functiontoCall : () => void) : void
    {
        var object = {tags: tags};
        (new Ajax).post('/ajaxdatablocks/get_multiple_by_tags', object, functiontoCall);
    }

}

/**
 * used for DataBlock responses
 */
interface AjaxDataBlockReponse extends AjaxResponse
{
    payload : PlainDataBlock;
}