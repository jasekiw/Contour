define(["require", "exports", "../Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    /**
     * Created by Jason on 12/22/2015.
     */
    var DataBlockPopulator = (function () {
        function DataBlockPopulator(rowTags, columnTags) {
            this.rowIndex = 0;
            this.columnIndex = 0;
            this.rows = rowTags;
            this.columns = columnTags;
        }
        DataBlockPopulator.prototype.getNextDataBlock = function (functionToCall) {
            if (this.rowIndex >= this.rows.length) {
                functionToCall(null);
                return;
            }
            console.log("tags to send");
            console.log({ tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id) });
            (new Ajax_1.Ajax).post("/api/datablocks/get_by_tags", { tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id) }, function (e) { return functionToCall(e); });
            this.columnIndex++;
            if (this.columnIndex >= this.columns.length) {
                this.columnIndex = 0;
                this.rowIndex++;
            }
        };
        return DataBlockPopulator;
    }());
    exports.DataBlockPopulator = DataBlockPopulator;
});
//# sourceMappingURL=DataBlockPopulator.js.map