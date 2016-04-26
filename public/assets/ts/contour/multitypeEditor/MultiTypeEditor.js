define(["require", "exports", "./DataBlockEditor", "./SheetEdtior"], function (require, exports, DataBlockEditor_1, SheetEdtior_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var MultiTypeEditor = (function () {
        function MultiTypeEditor() {
            this.dataBlockEditor = new DataBlockEditor_1.DataBlockEditor();
            this.sheetEditor = new SheetEdtior_1.SheetEditor(this.dataBlockEditor);
        }
        return MultiTypeEditor;
    }());
    exports.MultiTypeEditor = MultiTypeEditor;
});
//# sourceMappingURL=MultiTypeEditor.js.map