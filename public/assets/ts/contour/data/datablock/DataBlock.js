define(["require", "exports"], function (require, exports) {
    "use strict";
    /**
     * Created by Jason on 12/22/2015.
     */
    var DataBlock = (function () {
        function DataBlock() {
        }
        DataBlock.prototype.fromPlainObject = function (obj) {
            this.id = obj.id;
            this.type = obj.type;
            this.value = obj.value;
        };
        DataBlock.prototype.toPlainObject = function () {
            return { id: this.id, value: this.value, type: this.type };
        };
        return DataBlock;
    }());
    exports.DataBlock = DataBlock;
});
//# sourceMappingURL=DataBlock.js.map