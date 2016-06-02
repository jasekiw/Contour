import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {TagContextMenuHandler} from "../ui/contextMenus/TagContextMenuHandler";
import {ListContextMenu} from "../ui/contextMenus/ListContextMenu";
import {TagsEditor} from "./TagsEditor";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class Editor
{
    public dataBlockEditor : DataBlockEditor;
    private tagsEditor : TagsEditor;
    public sheetEditors : {[name : string]: SheetEditor} = {};
    private tagContextMenuHandler : TagContextMenuHandler;
    private editorTagContextMenuHandler : TagContextMenuHandler;
    private listContextMenu : ListContextMenu;

    constructor()
    {
        
        this.initialize();
        $(".nav-pills li a").click((e) => this.loadNewEditor(e));
    }

    /**
     * initializes the editor on the active tab
     */
    protected initialize()
    {
        this.tagsEditor = new TagsEditor();
        this.dataBlockEditor = new DataBlockEditor(this.tagsEditor);
        this.editorTagContextMenuHandler = new TagContextMenuHandler(".tagsEditor .currentTags .general_tags .editor_tag");
        this.tagContextMenuHandler = new TagContextMenuHandler(".tag");
        this.listContextMenu = new ListContextMenu(".GeneralListHandle");
        this.listContextMenu.setEditTagsFunction( ($elem) => this.handleEditTagsContextMenu($elem));
        let active = $(".tab-pane.active");
        let id = "#" + active.attr("id");
        this.sheetEditors[id] = (new SheetEditor(active.find(".editor"), this.dataBlockEditor, this.tagsEditor));
    }
    protected handleEditTagsContextMenu($elem : JQuery)
    {
        let id = "#" + $elem.parents(".sheet_editor").parents(".tab-pane").attr("id");
        if(this.sheetEditors[id] != undefined)
            this.sheetEditors[id].editTagsOnGeneralList($elem);

    }

    /**
     * initializes a new editor when not loaded when the user clicks on the nav button
     * @param e
     */
    protected loadNewEditor(e : JQueryEventObject)
    {
        let id = $(e.target).attr("href");
        //let id = this.formatId($(e.target).attr("href"));
        let targetSheet = $(id).find(".editor");
        if(this.sheetEditors[id] == undefined)
            this.sheetEditors[id] = new SheetEditor(targetSheet, this.dataBlockEditor, this.tagsEditor);
    }


}
