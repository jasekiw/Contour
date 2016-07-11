import {ExcelImportPage} from "./excelImport/ExcelImporterPage";
import {NewFacilityPage} from "./newfacility/NewFacilityPage";
import {Editor} from "./editor/Editor";
import {MenuEditor} from "./menu_editor/MenuEditor";
import {TagManager} from "./tag/TagManager";



class Main
{

    public excelImportPage : ExcelImportPage;
    public newFacilityPage : NewFacilityPage;
    private multiTypeEditor : Editor;
    private menuEditor : MenuEditor;
    private tagManager : TagManager;
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
        this.multiTypeEditor = new Editor();
    }

    private contains(value : string)
    {
        return this.classes.indexOf(value) !== -1;
    }

    private executeMenuEditor()
    {

        this.menuEditor = new MenuEditor($(".menuEditor"));
    }
    private executeTagManagement()
    {
        this.tagManager = new TagManager();
    }

}
new Main();