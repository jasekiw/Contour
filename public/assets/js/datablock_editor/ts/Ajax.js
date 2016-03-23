/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var Ajax = (function () {
    function Ajax() {
    }
    Ajax.prototype.get = function (url, functiontoCall) {
        $.ajax({
            type: "GET",
            url: url,
            success: function (e) {
                functiontoCall(e);
            },
            dataType: 'json'
        }).fail(function (e) {
            functiontoCall(e);
        });
    };
    Ajax.prototype.post = function (url, data, functiontoCall) {
        $.ajax({
            type: "POST",
            url: url,
            success: function (e) {
                functiontoCall(e);
            },
            data: data,
            dataType: 'json'
        }).fail(function (e) {
            functiontoCall(e);
        });
    };
    return Ajax;
})();
//# sourceMappingURL=Ajax.js.map