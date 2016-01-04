/**
 * Created by Jason Gallavin on 12/22/2015.
 */
    ///<reference path="references.d.ts" />
class Ajax
{
    public get(url :string , functiontoCall : (e) => void)
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
    public post(url :string , data : Object,  functiontoCall : (e) => void)
    {
        $.ajax( {
                type: "POST",
                url: url,
                success: (e) =>
                {
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }
        ).fail((e) => {

            functiontoCall(e);
        });
    }
}