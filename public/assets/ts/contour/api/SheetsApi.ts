import {Ajax, AjaxResponse} from "../Ajax";

export class SheetsApi
{
    public static get(id : number, functionToCall : (e : string) => void)
    {
        new Ajax().get("/api/sheets/show/" + id,
            (e : AjaxResponse) => {
                if (e.success)
                    if (functionToCall !== undefined)
                        functionToCall(e.payload);
            });
    }
}