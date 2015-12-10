/**
 * Created by Jason on 11/4/2015.
 */


function DatatagInterfacer() {


    this.getChildren = function(id, functiontoCall)
    {
        ajaxGet('/ajaxtags/get_children/' + id, functiontoCall);
    };

    this.getChildrenRecursive = function(id, functiontoCall)
    {
        ajaxGet('/ajaxtags/get_children_recursive/' + id, functiontoCall);

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
    }

}