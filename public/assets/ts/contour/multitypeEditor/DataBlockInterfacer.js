define(["require", "exports", "../Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var DataBlockInterfacer = (function () {
        function DataBlockInterfacer() {
        }
        DataBlockInterfacer.getById = function (id, functiontoCall) {
            (new Ajax_1.Ajax).get('/ajaxdatablocks/' + id, functiontoCall);
        };
        DataBlockInterfacer.getMultipleByTags = function (tags, functiontoCall) {
            var object = { tags: tags };
            (new Ajax_1.Ajax).post('/ajaxdatablocks/get_multiple_by_tags', object, functiontoCall);
        };
        return DataBlockInterfacer;
    }());
    exports.DataBlockInterfacer = DataBlockInterfacer;
});
//# sourceMappingURL=DataBlockInterfacer.js.map