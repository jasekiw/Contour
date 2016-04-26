define(["require", "exports", "./DataBlockViewer"], function (require, exports, DataBlockViewer_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 12/22/2015.
     */
    var Viewer = (function () {
        function Viewer() {
            var sheet = parseInt($(".excel_viewer_container").attr("sheet"));
            this.dataBlockviewer = new DataBlockViewer_1.DataBlockViewer(sheet);
        }
        return Viewer;
    }());
    exports.Viewer = Viewer;
});
//# sourceMappingURL=Viewer.js.map