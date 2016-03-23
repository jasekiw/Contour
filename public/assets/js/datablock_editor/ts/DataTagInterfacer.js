/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var DataTagInterfacer = (function () {
    function DataTagInterfacer() {
    }
    DataTagInterfacer.getChildren = function (id, functiontoCall) {
        (new Ajax).get('/ajaxtags/get_children/' + id, functiontoCall);
    };
    DataTagInterfacer.getChildrenRecursive = function (id, functiontoCall) {
        (new Ajax).get('/ajaxtags/get_children_recursive/' + id, functiontoCall);
    };
    return DataTagInterfacer;
})();
//# sourceMappingURL=DataTagInterfacer.js.map