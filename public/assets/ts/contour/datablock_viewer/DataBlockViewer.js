define(["require", "exports", "./DataBlockPopulator", "../Ajax"], function (require, exports, DataBlockPopulator_1, Ajax_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var DataBlockViewer = (function () {
        function DataBlockViewer(id) {
            var _this = this;
            this.currentRow = 0;
            this.excelViewerContainer = $(".excel_viewer_container");
            this.columns = [];
            this.rows = [];
            (new Ajax_1.Ajax()).get("/api/tags/get_children_recursive/" + id, function (e) {
                if (!e.success)
                    console.log("error has occurred when getting children");
                else {
                    e.tags.forEach(function (tag, index) {
                        console.log(tag);
                        if (tag.type == "row")
                            _this.rows.push(tag);
                        else if (tag.type == "column")
                            _this.columns.push(tag);
                    });
                    _this.populateTableTags();
                }
            });
        }
        DataBlockViewer.prototype.populateTableTags = function () {
            var _this = this;
            this.excelViewerContainer.append('<table class="excel_viewer"><thead></thead><tbody></tbody></table>');
            this.header = $('.excel_viewer > thead');
            this.body = $('.excel_viewer > tbody');
            this.header.append('<th></th>');
            this.columns.forEach(function (tag, index) {
                _this.header.append('<th tag="' + tag.id + '">' + tag.name + '</th>');
            });
            this.rows.forEach(function (tag, index) {
                _this.body.append('<tr tag="' + tag.id + '"><td class="column_name_container">' + tag.name + '</td></tr>');
            });
            this.populateTableDataBlocks();
        };
        DataBlockViewer.prototype.populateTableDataBlocks = function () {
            var _this = this;
            this.populator = new DataBlockPopulator_1.DataBlockPopulator(this.rows, this.columns);
            this.populator.getNextDataBlock(function (e) { return _this.addDataBlock(e); });
        };
        DataBlockViewer.prototype.addDataBlock = function (e) {
            var _this = this;
            if (e.success) {
                if (e.datablock == null)
                    return;
                console.log(e.datablock);
                this.body.find('tr').eq(this.currentRow).append('<td class="cell">' + e.datablock.value + '</td>');
            }
            else {
                this.body.find('tr').eq(this.currentRow).append('<td class="cell">' + '</td>');
            }
            this.currentRow = this.populator.rowIndex; // gets the row index before it advances
            this.populator.getNextDataBlock(function (e) { return _this.addDataBlock(e); });
        };
        return DataBlockViewer;
    }());
    exports.DataBlockViewer = DataBlockViewer;
});
//# sourceMappingURL=DataBlockViewer.js.map