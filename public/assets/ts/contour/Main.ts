import {ExcelImportPage} from "excelImport/ExcelImporterPage";
import {NewFacilityPage} from "newfacility/NewFacilityPage";
import {MultiTypeEditor} from "./multitypeEditor/MultiTypeEditor";
import {MenuEditor} from "./menu_editor/MenuEditor";
import {Types} from "./api/Types"

class Main
{

    public excelImportPage : ExcelImportPage;
    public newFacilityPage : NewFacilityPage;
    private multiTypeEditor : MultiTypeEditor;
    private menuEditor : MenuEditor;
    private classes : string;

    constructor()
    {
        var $body = $("body");
        //$body.on("contextmenu", (e : JQueryEventObject) =>
        //{
        //    if(e.ctrlKey)
        //        return true;
        //    else
        //    {
        //        var tag = e.target.tagName.toUpperCase();
        //        if(tag == "P" || tag.indexOf("H") > -1 ||tag == "SPAN")
        //            return true;
        //    }
        //    return false;
        //});
        this.classes = $body.attr('class');
        if (this.contains('SheetsController@edit'))
            this.executemultiTypeEdtior();
        if (this.contains('MenuController@edit'))
            this.executeMenuEditor();
        if (this.contains('SheetsController@createFacility'))
            this.executeNewFacility();
        if (this.contains('ExcelImporterController@index'))
            this.executeExcelImport();

    }

    private executeExcelImport() : void
    {
        this.excelImportPage = new ExcelImportPage();
    }

    private executeNewFacility()
    {
        this.newFacilityPage = new NewFacilityPage();
    }

    private executemultiTypeEdtior()
    {
        this.multiTypeEditor = new MultiTypeEditor();
    }

    private contains(value : string)
    {
        return this.classes.indexOf(value) !== -1;
    }

    private executeMenuEditor()
    {

        this.menuEditor = new MenuEditor($(".menuEditor"));
    }

}
new Main();