/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class Ajax
{
    /**
     * submits a get request to the url and expects a json repsonse back
     * @param url
     * @param functiontoCall
     */
    public get(url : string, functiontoCall? : (e : {}) => void)
    {
        $.ajax({
                type:    "GET",
                url:     url,
                success: (e) =>
                         {
                             if (functiontoCall !== undefined)
                                 functiontoCall(e);
                         },

                dataType: 'json'
            }
        ).fail((e) =>
        {
            console.log("call failed");
            console.log(e);
            if (functiontoCall !== undefined)
                functiontoCall(e);
        });
    }

    /**
     * submits a post request to the url and expects a json repsonse back
     * @param url
     * @param data
     * @param functiontoCall
     */
    public post(url : string, data : any, functiontoCall? : (e : {}) => void)
    {
        $.ajax({
                method:   "POST",
                url:      url,
                success:  (e) =>
                          {
                              if (!e.success) {
                                  console.log("call failed");
                                  console.log(e);
                              }
                              if (functiontoCall !== undefined)
                                  functiontoCall(e);
                          },
                data:     data,
                dataType: 'json'
            }
        ).fail((e) =>
        {

            console.log("call failed");
            console.log(e);
            if (functiontoCall !== undefined)
                functiontoCall(e);
        });
    }

    /**
     * submits a PUT request to the url and expects a json repsonse back
     * @param url
     * @param data
     * @param functiontoCall
     */
    public put(url : string, data, functiontoCall? : (e : {}) => void)
    {
        $.ajax({
                type:     "PUT",
                url:      url,
                success:  (e) =>
                          {
                              if (!e.success) {
                                  console.log("call failed");
                                  console.log(e);
                              }
                              if (functiontoCall !== undefined)
                                  functiontoCall(e);
                          },
                data:     data,
                dataType: 'json'
            }
        ).fail((e) =>
        {
            if (functiontoCall !== undefined)
                functiontoCall(e);
        });
    }
}

export interface AjaxData
{
    success : boolean;
    message : string;
    payload : any;
}