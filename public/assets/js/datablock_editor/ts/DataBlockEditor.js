/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
var DataBlockEditor = (function () {
    function DataBlockEditor() {
        var _this = this;
        this.datablockInterfacer = new DataBlockInterfacer();
        this.dataTagInterfacer = new DataTagInterfacer();
        this.dataBlockEditor = $("#DatablockEditor");
        this.dataBlockFormula = this.dataBlockEditor.find("input[name='datablock_value']");
        this.dataBlockContainer = this.dataBlockEditor.find(".datablocks");
        this.head = this.dataBlockEditor.find(".header_container");
        this.body = this.dataBlockEditor.find(".row_and_datablock_container");
        this.searchBar = this.dataBlockEditor.find("[name=search]");
        this.searchBar.change(function () {
            _this.filterView(_this.searchBar.val());
        });
    }
    /**
     * Clears the the datablocks our from the container
     */
    DataBlockEditor.prototype.clearDatablocks = function () {
        this.dataBlockContainer.html("");
    };
    DataBlockEditor.prototype.addDataBlock = function (data) {
        this.body.append(data);
    };
    DataBlockEditor.prototype.addtoHead = function (data) {
        this.head.append(data);
    };
    DataBlockEditor.prototype.removeDatablocks = function () {
        this.body.find(".datablock").remove();
    };
    DataBlockEditor.prototype.removeHeadTags = function () {
        this.head.html("");
    };
    DataBlockEditor.prototype.removeRowTags = function () {
        this.body.find(".rowTag").remove();
    };
    DataBlockEditor.prototype.filterView = function (filterText) {
        console.log("filtering for" + filterText);
    };
    /**
     * Show the editor with the current sheet it
     * @param id
     * @param value
     */
    DataBlockEditor.prototype.show = function (id, value) {
        DataTagInterfacer.getChildrenRecursive(id, this.populateEditor);
        this.dataBlockFormula.val(value);
        this.dataBlockEditor.show();
    };
    /**
     *
     * @param data
     */
    DataBlockEditor.prototype.populateEditor = function (data) {
        var _this = this;
        //var thisfunction = this;
        // console.log(data);
        if (data.success) {
            this.removeHeadTags();
            this.removeRowTags();
            data.tags.forEach(function (element, index) {
                var dataToAppend = "<tr class='rowTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' ><td class='tag'>" + element.name + "</td></tr>";
                if (element.type.toUpperCase() == "COLUMN") {
                    _this.head.append("<th class='headTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' >" + element.name + "</th>");
                }
                else if (element.type.toUpperCase() == "ROW") {
                    //var numberOfRows = this.body.find(".rowTag").length; // get number of rows
                    if (_this.body.find(".rowTag").length > 0) {
                        if (parseInt(_this.body.find(".rowTag").last().attr("sort_number")) < element.sort_number) {
                            _this.body.find(".rowTag").last().after(dataToAppend);
                        }
                        else if (parseInt(_this.body.find(".rowTag").first().attr("sort_number")) > element.sort_number) {
                            _this.body.find(".rowTag").last().before(dataToAppend);
                        }
                        else {
                            setTimeout(function () {
                                _this.cicleThroughandAdd(".rowTag", dataToAppend, element);
                            }, 1); //fake multithreading
                        }
                    }
                    else {
                        _this.body.append(dataToAppend);
                    }
                }
            });
        }
        else {
            window.alert("something went wrong when pupulating the editor");
        }
    };
    DataBlockEditor.prototype.cicleThroughandAdd = function (classtoLoop, dataToAppend, element) {
        var _this = this;
        var numberOfRows = this.body.find(".rowTag").length;
        this.body.find(classtoLoop).each(function (index) {
            var sortNumber = parseInt($(_this).attr("sort_number"));
            if ((numberOfRows - 1) > index && sortNumber > element.sort_number) {
                $(_this).before(dataToAppend);
                return false;
            }
            else if ((numberOfRows - 1) == index) {
                if (sortNumber > element.sortNumber) {
                    $(_this).before(dataToAppend);
                    return false;
                }
                else {
                    $(_this).after(dataToAppend);
                    return false;
                }
            }
        });
    };
    return DataBlockEditor;
})();
//# sourceMappingURL=DataBlockEditor.js.map