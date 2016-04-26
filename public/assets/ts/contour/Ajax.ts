/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class Ajax
{
    /**
     *
     * @param url
     * @param functiontoCall
     */
    public get(url :string , functiontoCall : (e ) => void)
    {
        $.ajax( {
                type: "GET",
                url: url,
                success: (e) =>
                {
                    functiontoCall(e);
                },

                dataType: 'json'
            }
        ).fail((e) => {
            functiontoCall(e);
        });
    }

    /**
     *
     * @param url
     * @param data
     * @param functiontoCall
     */
    public post(url :string , data  ,  functiontoCall : (e ) => void)
    {
        $.ajax( {
                type: "POST",
                url: url,
                success: (e) =>
                {
                    e.success = true;
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }
        ).fail((e ) => {
            e.success = false;
            functiontoCall(e);
        });
    }

    /**
     *
     * @param url
     * @param data
     * @param functiontoCall
     */
    public put(url :string , data ,  functiontoCall : (e ) => void)
    {
        $.ajax( {
                type: "PUT",
                url: url,
                success: (e) =>
                {
                    e.success = true;
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }
        ).fail((e ) => {
            e.success = false;
            functiontoCall(e);
        });
    }
}

export interface AjaxData {
    success : boolean;
    message : string;
    payload : any;

}