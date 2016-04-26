define(["require", "exports", "../Ajax", "../ui/NewTagDialog", "../components/MouseHandler"], function (require, exports, Ajax_1, NewTagDialog_1, MouseHandler_1) {
    "use strict";
    /**
     * Created by Jason on 1/12/2016.
     */
    var SheetEditor = (function () {
        function SheetEditor(dataBlockEditor) {
            var _this = this;
            this.newTagDialog = new NewTagDialog_1.NewTagDialog();
            this.sheet = $('.sheet_editor').first();
            this.dataBlockEditor = dataBlockEditor;
            $(".cell input").dblclick(function (e) {
                e.preventDefault();
                var thisElement = $(e.currentTarget);
                var excelSheet = parseInt($(".excel_editor").attr("sheet"));
                _this.dataBlockEditor.open(thisElement, excelSheet, thisElement.val());
            });
            $(".cell input").focusin(function (e) {
                var currentElement = $(e.currentTarget);
                _this.sheet = currentElement.parents('.sheet_editor');
                _this.sheet.find('.sheet_row').each(function (index, element) {
                    var $element = $(element);
                    $element.removeClass('current_row');
                });
                currentElement.parents('.sheet_row').addClass('current_row');
                console.log(currentElement.parents('.sheet_row'));
                _this.currentText = $(e.currentTarget).val();
            });
            $(".cell input").focusout(function (e) {
                if ($(e.currentTarget).val() != _this.currentText)
                    (new Ajax_1.Ajax).post("/api/datablocks/save/" + $(e.currentTarget).attr("datablock"), { value: $(e.currentTarget).val() }, function () { });
            });
            this.setupSheetControls();
        }
        SheetEditor.prototype.setupSheetControls = function () {
            var _this = this;
            var headerRow = this.sheet.find("thead > tr");
            var newColumnTagTemplate = "<th class=\"new_column\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i></th>";
            headerRow.append($(newColumnTagTemplate));
            this.addColumn = headerRow.find(".new_column i");
            var body = this.sheet.find("tbody");
            var newRowTemplate = "<tr class=\"new_row\"><td class=\"row_name\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i></td></tr>";
            body.append($(newRowTemplate));
            this.addRow = body.find(".new_row i");
            this.addColumn.click(function () { return _this.showNewTagDialog("column"); });
            this.addRow.click(function () { return _this.showNewTagDialog("row"); });
        };
        SheetEditor.prototype.showNewTagDialog = function (type) {
            var _this = this;
            this.newTagDialog.show(function (e) { return _this.handleNewTag(e); }, parseInt(this.sheet.attr("sheet")), type, MouseHandler_1.mouse.x, MouseHandler_1.mouse.y);
        };
        SheetEditor.prototype.handleNewTag = function (tag) {
        };
        return SheetEditor;
    }());
    exports.SheetEditor = SheetEditor;
});
//# sourceMappingURL=SheetEdtior.js.map