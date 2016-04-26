define(["require", "exports", "../Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var DataTagInterfacer = (function () {
        function DataTagInterfacer() {
        }
        DataTagInterfacer.getChildren = function (id, functiontoCall) {
            (new Ajax_1.Ajax).get('/ajaxtags/get_children/' + id, functiontoCall);
        };
        DataTagInterfacer.getChildrenRecursive = function (id, functiontoCall) {
            (new Ajax_1.Ajax).get('/ajaxtags/get_children_recursive/' + id, functiontoCall);
        };
        return DataTagInterfacer;
    }());
    exports.DataTagInterfacer = DataTagInterfacer;
});
//# sourceMappingURL=DataTagInterfacer.js.map