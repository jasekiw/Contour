/**
 * Created by Jason on 11/4/2015.
 */
function DataBlockInterfacer() {



    this.get_by_id = function(id, functiontoCall)
    {
        ajaxGet('/ajaxdatablocks/' + id, functiontoCall);
    };

    this.get_multiple_by_tags = function(tags, functiontoCall)
    {
        var object = {tags: tags};
        ajaxPost('/ajaxdatablocks/get_multiple_by_tags', object, functiontoCall);
    };



    var ajaxGet = function (url, functiontoCall)
    {
        $.ajax( {
                type: "GET",
                url: url,
                success: function(e)
                {
                    functiontoCall(e);
                },

                dataType: 'json'
            }
        ).fail(function(e) {

            functiontoCall(e);
        });
    };


    var ajaxPost = function(url, data, functionToCall)
    {
        $.ajax( {
                type: "POST",
                url: url,
                success: function(e)
                {
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }
        ).fail(function(e) {

            functiontoCall(e);
        });
    }
}