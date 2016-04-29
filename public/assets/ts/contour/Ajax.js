define(["require", "exports"], function (require, exports) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var Ajax = (function () {
        function Ajax() {
        }
        /**
         * submits a get request to the url and expects a json repsonse back
         * @param url
         * @param functiontoCall
         */
        Ajax.prototype.get = function (url, functiontoCall) {
            $.ajax({
                type: "GET",
                url: url,
                success: function (e) {
                    functiontoCall(e);
                },
                dataType: 'json'
            }).fail(function (e) {
                console.log("call failed");
                console.log(e);
                functiontoCall(e);
            });
        };
        /**
         * submits a post request to the url and expects a json repsonse back
         * @param url
         * @param data
         * @param functiontoCall
         */
        Ajax.prototype.post = function (url, data, functiontoCall) {
            $.ajax({
                method: "POST",
                url: url,
                success: function (e) {
                    if (!e.success) {
                        console.log("call failed");
                        console.log(e);
                    }
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }).fail(function (e) {
                console.log("call failed");
                console.log(e);
                functiontoCall(e);
            });
        };
        /**
         * submits a PUT request to the url and expects a json repsonse back
         * @param url
         * @param data
         * @param functiontoCall
         */
        Ajax.prototype.put = function (url, data, functiontoCall) {
            $.ajax({
                type: "PUT",
                url: url,
                success: function (e) {
                    if (!e.success) {
                        console.log("call failed");
                        console.log(e);
                    }
                    functiontoCall(e);
                },
                data: data,
                dataType: 'json'
            }).fail(function (e) {
                functiontoCall(e);
            });
        };
        return Ajax;
    }());
    exports.Ajax = Ajax;
});
//# sourceMappingURL=Ajax.js.map