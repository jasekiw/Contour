/**
 * Created by Jason Gallavin on 3/25/2016.
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    var ExcelImportPage = (function () {
        function ExcelImportPage() {
            var _this = this;
            $(".importButton").on("click", function () { return _this.uploadExcelFile(); });
        }
        ExcelImportPage.prototype.uploadExcelFile = function () {
            var _this = this;
            var data = new FormData();
            var input = $('.btn-file input')[0];
            if (input.files.length == 0) {
                alert("You must select an excel file to upload first");
                return;
            }
            data.append("excelFile", input.files[0]);
            jQuery.ajax({
                url: '/import/upload',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                type: 'POST',
                success: function (data) {
                    if (!data.success)
                        alert("upload failed");
                    else
                        _this.startImport();
                }
            });
        };
        ExcelImportPage.prototype.startImport = function () {
            var data = new FormData();
            var suiteName = $("#suite").val();
            data.append("suite", suiteName);
            var importLocation = $("#importLocation").find("input").val();
            data.append("importLocation", importLocation);
            var progress = $(".progress");
            progress.text("Import started...");
            progress.show();
            jQuery.ajax({
                url: '/import/start',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                timeout: 1200000,
                complete: function (jqXHR) { return progress.html(jqXHR.responseText); },
                error: function (jqXHR) { return progress.html("ERROR: " + jqXHR.responseText); }
            });
        };
        return ExcelImportPage;
    }());
    exports.ExcelImportPage = ExcelImportPage;
});
//# sourceMappingURL=ExcelImporterPage.js.map