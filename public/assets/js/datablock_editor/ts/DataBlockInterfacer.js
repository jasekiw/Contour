/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var DataBlockInterfacer = (function () {
    function DataBlockInterfacer() {
    }
    DataBlockInterfacer.getById = function (id, functiontoCall) {
        (new Ajax).get('/ajaxdatablocks/' + id, functiontoCall);
    };
    DataBlockInterfacer.getMultipleByTags = function (tags, functiontoCall) {
        var object = { tags: tags };
        (new Ajax).post('/ajaxdatablocks/get_multiple_by_tags', object, functiontoCall);
    };
    return DataBlockInterfacer;
})();
//# sourceMappingURL=DataBlockInterfacer.js.map