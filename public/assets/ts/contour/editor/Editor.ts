import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {TagContextMenuHandler} from "../ui/contextMenus/TagContextMenuHandler";
import {ListContextMenu} from "../ui/contextMenus/ListContextMenu";
import {TagsEditor} from "./TagsEditor";
import {ConfirmDialog} from "../ui/dialogs/ConfirmDialog";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class Editor
{
    public dataBlockEditor : DataBlockEditor;
    private tagsEditor : TagsEditor;
    public sheetEditors : {[name : string] : SheetEditor} = {};
    private tagContextMenuHandler : TagContextMenuHandler;
    private editorTagContextMenuHandler : TagContextMenuHandler;
    private listContextMenu : ListContextMenu;
    private deleteConfirmation : ConfirmDialog;
    constructor()
    {

        this.initialize();
        $(".nav-pills li a").click((e) => this.loadNewEditor(e));
    }

    protected initializeSheetControls()
    {
        let newSheetTemplate = `<div class="new_sheet"><i class="fa fa-plus" aria-hidden="true"></i></div>`;
        let deleteSheeetTemplate = `<div class="delete_sheet"><i class="fa fa-trash" aria-hidden="true"></i></div>`;
        $('.nav.nav-pills li a').append(newSheetTemplate);
        $('.nav.nav-pills li a').prepend(deleteSheeetTemplate);
        $('.nav.nav-pills li a .new_sheet').click((e : JQueryEventObject) =>
        {
            this.deleteConfirmation.show(() => {

            });
            e.stopPropagation();
        });
        $('.nav.nav-pills li a .delete_sheet').click((e : JQueryEventObject) =>
        {
            alert('clicked');
            e.stopPropagation();
        });
        //hover animation for sliding effect
        $('.nav.nav-pills li a').hover((e : JQueryEventObject) =>
        {

            let target = $(e.target).find(".new_sheet, .delete_sheet");
            target.show();
            let startHeight = target.height();
            console.log(startHeight);
            target.css({display: ""});
            target.css({width: 0, height: startHeight, "text-align": "center"});
            target.animate(
                {
                    width: 20.38
                },
                150,
                () => target.css({width: "", height: "", "text-align": ""})
            );

        },
            () => void(0) // the hover off function does not need to do anything.
            //// this is here to allow the first function to only be triggered on hover in
        );
    }




    /**
     * initializes the editor on the active tab
     */
    protected initialize()
    {
        this.deleteConfirmation = new ConfirmDialog();
        this.tagsEditor = new TagsEditor();
        this.dataBlockEditor = new DataBlockEditor(this.tagsEditor);
        this.editorTagContextMenuHandler = new TagContextMenuHandler(".tagsEditor .currentTags .general_tags .editor_tag");
        this.tagContextMenuHandler = new TagContextMenuHandler(".tag");
        this.listContextMenu = new ListContextMenu(".GeneralListHandle");
        this.listContextMenu.setEditTagsFunction(($elem) => this.handleEditTagsContextMenu($elem));
        let active = $(".tab-pane.active");
        let id = "#" + active.attr("id");
        this.sheetEditors[id] = (new SheetEditor(active.find(".editor"), this.dataBlockEditor, this.tagsEditor));
        this.initializeSheetControls();
    }

    protected handleEditTagsContextMenu($elem : JQuery)
    {
        let id = "#" + $elem.parents(".sheet_editor").parents(".tab-pane").attr("id");
        if (this.sheetEditors[id] != undefined)
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
        if (this.sheetEditors[id] == undefined)
            this.sheetEditors[id] = new SheetEditor(targetSheet, this.dataBlockEditor, this.tagsEditor);
    }

}
