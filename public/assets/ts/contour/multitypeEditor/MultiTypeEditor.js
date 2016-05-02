define(["require", "exports", "./DataBlockEditor", "./SheetEditor", "../ui/TagContextMenuHandler"], function (require, exports, DataBlockEditor_1, SheetEditor_1, TagContextMenuHandler_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var MultiTypeEditor = (function () {
        function MultiTypeEditor() {
            var _this = this;
            this.sheetEditors = [];
            this.tagContextMenuHandler = new TagContextMenuHandler_1.TagContextMenuHandler(".tag");
            this.dataBlockEditor = new DataBlockEditor_1.DataBlockEditor();
            $(".sheet_editor").each(function (index, element) {
                _this.sheetEditors.push(new SheetEditor_1.SheetEditor(_this.dataBlockEditor, element));
            });
        }
        return MultiTypeEditor;
    }());
    exports.MultiTypeEditor = MultiTypeEditor;
});
//# sourceMappingURL=MultiTypeEditor.js.map