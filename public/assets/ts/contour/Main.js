define(["require", "exports", "excelImport/ExcelImporterPage", "newfacility/NewFacilityPage", "./multitypeEditor/MultiTypeEditor", "./menu_editor/MenuEditor"], function (require, exports, ExcelImporterPage_1, NewFacilityPage_1, MultiTypeEditor_1, MenuEditor_1) {
    "use strict";
    var Main = (function () {
        function Main() {
            $("body").on("contextmenu", function (e) {
                return e.ctrlKey;
            });
            this.classes = $('body').attr('class');
            if (this.contains('SheetsController@edit'))
                this.executemultiTypeEdtior();
            if (this.contains('MenuController@edit'))
                this.executeMenuEditor();
            if (this.contains('SheetsController@createFacility'))
                this.executeNewFacility();
            if (this.contains('ExcelImporterController@index'))
                this.executeExcelImport();
        }
        Main.prototype.executeExcelImport = function () {
            this.excelImportPage = new ExcelImporterPage_1.ExcelImportPage();
        };
        Main.prototype.executeNewFacility = function () {
            this.newFacilityPage = new NewFacilityPage_1.NewFacilityPage();
        };
        Main.prototype.executemultiTypeEdtior = function () {
            this.multiTypeEditor = new MultiTypeEditor_1.MultiTypeEditor();
        };
        Main.prototype.contains = function (value) {
            return this.classes.indexOf(value) !== -1;
        };
        Main.prototype.executeMenuEditor = function () {
            this.menuEditor = new MenuEditor_1.MenuEditor($(".menuEditor"));
        };
        return Main;
    }());
    new Main();
});
//# sourceMappingURL=Main.js.map