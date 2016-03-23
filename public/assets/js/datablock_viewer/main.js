/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var Main = (function () {
    function Main() {
        var sheet = parseInt($(".excel_viewer_container").attr("sheet"));
        this.dataBlockviewer = new DataBlockViewer(sheet);
    }
    return Main;
})();
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var DataBlockViewer = (function () {
    function DataBlockViewer(id) {
        var _this = this;
        this.currentRow = 0;
        this.excelViewerContainer = $(".excel_viewer_container");
        this.columns = [];
        this.rows = [];
        (new Ajax()).get("/api/tags/get_children_recursive/" + id, function (e) {
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
        this.populator = new DataBlockPopulator(this.rows, this.columns);
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
})();
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="../js/jquery/jquery.d.ts" />
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
                e.success = true;
                functiontoCall(e);
            },
            data: data,
            dataType: 'json'
        }).fail(function (e) {
            e.success = false;
            functiontoCall(e);
        });
    };
    return Ajax;
})();
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
var DataTag = (function () {
    function DataTag() {
    }
    return DataTag;
})();
/**
 * Created by Jason on 12/22/2015.
 */
var DataBlock = (function () {
    function DataBlock() {
    }
    return DataBlock;
})();
/**
 * Created by Jason on 12/22/2015.
 */
///<reference path="references.d.ts" />
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
        (new Ajax).post("/api/datablocks/get_by_tags", { tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id) }, function (e) { return functionToCall(e); });
        this.columnIndex++;
        if (this.columnIndex >= this.columns.length) {
            this.columnIndex = 0;
            this.rowIndex++;
        }
    };
    return DataBlockPopulator;
})();
//# sourceMappingURL=main.js.map