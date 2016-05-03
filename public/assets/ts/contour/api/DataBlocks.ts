import {PlainDataBlock} from "../data/datablock/DataBlock";
import {Ajax, AjaxData} from "../Ajax";

export class DataBlocksApi
{

    /**
     *
     * @param tagIds The tags that will be assigned
     * @param type the name of the type to use
     * @param value
     * @param funtionToCall The function to call to give the tag object to
     */
    public static create(tagIds : number[], type : string, value : string, funtionToCall? : (e : PlainDataBlock) => void) : void
    {
        var data = {
            tagIds:  tagIds,
            value: value,
            type:  type
        };
        new Ajax().post("/api/datablocks/create",
            data
            , (e : AjaxDataBlockReponse) =>
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
            , (e : AjaxDataBlockReponse) =>
            {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);

            });
    }

}

/**
 * used for DataBlock responses
 */
interface AjaxDataBlockReponse extends AjaxData
{
    payload : PlainDataBlock;
}