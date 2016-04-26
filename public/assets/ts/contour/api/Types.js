define(["require", "exports", "../Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 4/21/2016.
     */
    var Types = (function () {
        function Types() {
        }
        /**
         *
         * @param {Function( AjaxTagArrayReponse ) } functionToCall
         */
        Types.getTagTypes = function (functionToCall) {
            new Ajax_1.Ajax().get("/api/tags/types", function (e) {
                functionToCall(e);
            });
        };
        return Types;
    }());
    exports.Types = Types;
});
//# sourceMappingURL=Types.js.map