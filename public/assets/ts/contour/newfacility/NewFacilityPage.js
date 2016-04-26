/**
 * Created by Jason on 4/7/2016.
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    var NewFacilityPage = (function () {
        function NewFacilityPage() {
            var _this = this;
            this.toProperties = function () {
                $(".step1").hide();
                $(".step2").show();
                window.scrollTo(0, 0);
            };
            this.finish = function () {
            };
            this.backToStart = function () {
                $(".step2").hide();
                $(".step1").show();
                window.scrollTo(0, 0);
            };
            $("#finish").click(function () { return _this.finish(); });
            $("#backToStart").click(function () { return _this.backToStart(); });
            $("#toProperties").click(function () { return _this.toProperties(); });
            $(".step1").show();
        }
        return NewFacilityPage;
    }());
    exports.NewFacilityPage = NewFacilityPage;
});
//# sourceMappingURL=NewFacilityPage.js.map