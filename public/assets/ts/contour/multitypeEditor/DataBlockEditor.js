define(["require", "exports", "../Ajax", "./DataBlockInterfacer", "./DataTagInterfacer", "./templates/DatablockEditorTemplate"], function (require, exports, Ajax_1, DataBlockInterfacer_1, DataTagInterfacer_1, DatablockEditorTemplate_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var DataBlockEditor = (function () {
        function DataBlockEditor() {
            var _this = this;
            $('body').append($(DatablockEditorTemplate_1.template));
            this.datablockInterfacer = new DataBlockInterfacer_1.DataBlockInterfacer();
            this.dataTagInterfacer = new DataTagInterfacer_1.DataTagInterfacer();
            this.dataBlockEditor = $("#DatablockEditor");
            this.dataBlockFormula = this.dataBlockEditor.find("input[name='datablock_value']");
            this.dataBlockContainer = this.dataBlockEditor.find(".datablocks");
            this.head = this.dataBlockEditor.find(".header_container");
            this.body = this.dataBlockEditor.find(".row_and_datablock_container");
            this.searchBar = this.dataBlockEditor.find("[name=search]");
            this.saveButton = this.dataBlockEditor.find("input.submit");
            this.cancelButton = this.dataBlockEditor.find("input.cancel");
            this.calculateButton = this.dataBlockEditor.find("input[name='calculate']");
            this.calculationOutputContainer = this.dataBlockEditor.find(".calculated");
            this.searchBar.change(function () {
                _this.filterView(_this.searchBar.val());
            });
            $("body").append('<div class="background_filter"></div>');
            this.backGroundFilter = $(".background_filter");
            this.dataBlockEditor.find(".exitButton").click(function () {
                _this.exit();
            });
            this.backGroundFilter.css("background", "black")
                .css("display", "none")
                .css("top", 0)
                .css("bottom", "0")
                .css("left", "0")
                .css("right", "0")
                .css("position", "fixed")
                .css("opacity", "0")
                .css("z-index", "8000");
            this.saveButton.on("click", function (e) {
                e.preventDefault();
                _this.save();
                _this.exit();
            });
            this.cancelButton.on("click", function (e) {
                e.preventDefault();
                _this.exit();
            });
            this.calculateButton.on("click", function (e) {
                (new Ajax_1.Ajax()).post("/api/getValue", { datablock: _this.dataBlockFormula.val(), datablockid: _this.cell.attr("datablock") }, function (response) {
                    _this.calculationOutputContainer.html(response.result);
                });
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
        DataBlockEditor.prototype.open = function (cell, sheetId, value) {
            this.cell = cell;
            this.show(sheetId, value);
        };
        /**
         * Show the editor with the current sheet it
         * @param id
         * @param value
         */
        DataBlockEditor.prototype.show = function (id, value) {
            //DataTagInterfacer.getChildrenRecursive(id, (e) => this.populateEditor(e) );
            this.dataBlockFormula.val(value);
            this.calculationOutputContainer.html("");
            this.dataBlockEditor.css("opacity", "0");
            this.dataBlockEditor.show();
            var offsetWidth = window.innerWidth / 2;
            var offsetHeight = window.innerHeight / 2;
            $("body").css("max-height", "100%").css("overflow-y", "hidden");
            this.dataBlockEditor.css("position", "fixed");
            this.dataBlockEditor.css("left", 10 + "px");
            this.dataBlockEditor.css("top", 10 + "px");
            this.dataBlockEditor.css("bottom", 10 + "px");
            this.dataBlockEditor.css("right", 10 + "px").css("z-index", "9001");
            this.backGroundFilter.show();
            this.backGroundFilter.animate({
                opacity: 0.4
            }, 500);
            this.dataBlockEditor.animate({
                opacity: 1
            }, 300);
        };
        DataBlockEditor.prototype.exit = function () {
            var _this = this;
            this.backGroundFilter.animate({
                opacity: 0
            }, 300, function () {
                _this.backGroundFilter.hide();
            });
            this.dataBlockEditor.animate({
                opacity: 0
            }, 300, function () {
                _this.dataBlockEditor.hide();
            });
            $("body").css("max-height", "").css("overflow-y", "");
        };
        DataBlockEditor.prototype.save = function () {
            (new Ajax_1.Ajax).post("/api/datablocks/save/" + this.cell.attr("datablock"), { value: this.dataBlockFormula.val() }, function () { });
            this.cell.val(this.dataBlockFormula.val());
        };
        /**
         *
         * @param data
         */
        DataBlockEditor.prototype.populateEditor = function (data) {
            var _this = this;
            //var thisfunction = this;
            console.log(data);
            console.debug(JSON.stringify(data));
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
                            if (parseInt(_this.body.find(".rowTag").last().attr("sort_number")) < element.sort_number)
                                _this.body.find(".rowTag").last().after(dataToAppend);
                            else if (parseInt(_this.body.find(".rowTag").first().attr("sort_number")) > element.sort_number)
                                _this.body.find(".rowTag").last().before(dataToAppend);
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
    }());
    exports.DataBlockEditor = DataBlockEditor;
});
//# sourceMappingURL=DataBlockEditor.js.map