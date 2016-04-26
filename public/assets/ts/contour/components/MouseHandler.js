define(["require", "exports"], function (require, exports) {
    "use strict";
    /**
     * Created by Jason Gallavin on 4/22/2016.
     */
    var MouseHandler = (function () {
        function MouseHandler() {
            var _this = this;
            this.x = 0;
            this.y = 0;
            $(document).mousemove(function (e) {
                _this.x = e.pageX;
                _this.y = e.pageY;
            });
        }
        return MouseHandler;
    }());
    exports.MouseHandler = MouseHandler;
    exports.mouse = new MouseHandler();
});
//# sourceMappingURL=MouseHandler.js.map