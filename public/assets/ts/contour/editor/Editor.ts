import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {TagContextMenuHandler} from "../ui/contextMenus/TagContextMenuHandler";
import {ListContextMenu} from "../ui/contextMenus/ListContextMenu";
import {TagsEditor} from "./TagsEditor";
import {ConfirmDialog} from "../ui/dialogs/ConfirmDialog";
import {TagsApi} from "../api/TagsApi";
import {NewNamedResourceDialog} from "../ui/dialogs/NewNamedResourceDialog";
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
    private newSheetConfirmation : NewNamedResourceDialog;
    constructor()
    {
        $(".upper-controls .delete").click((e : JQueryEventObject) => {
            e.preventDefault();
            this.deleteConfirmation.show(() => window.location.href = $(e.target).attr("href"))
        });
        this.initialize();
        $(".nav.nav-pills").on("click", "li a", (e) => this.loadNewEditor(e));
    }

    protected initializeSheetControls()
    {
        this.addSheetControls();
        $('.nav.nav-pills').on("click", " li a .new_sheet", (e : JQueryEventObject) =>this.newSheet(e));
        $('.nav.nav-pills').on("click", " li a .delete_sheet",(e : JQueryEventObject) => this.deleteSheet(e));

    }

    protected addSheetControls()
    {
        let newSheetTemplate = `<div class="new_sheet"><i class="fa fa-plus" aria-hidden="true"></i></div>`;
        let deleteSheeetTemplate = `<div class="delete_sheet"><i class="fa fa-trash" aria-hidden="true"></i></div>`;
        $('.nav.nav-pills li a').append(newSheetTemplate);
        $('.nav.nav-pills li a').prepend(deleteSheeetTemplate);
    }

    protected newSheet(e : JQueryEventObject)
    {

        let currentTab = $(e.target).parents("a");
        let currentLi = $(e.target).parents("li");
        let parent = parseInt(currentTab.attr("id"));
        let tabLink = currentTab.attr("href");
        this.newSheetConfirmation.show((name) => {
            TagsApi.create(name,parent, undefined, "folder", (e) => {
                let tabName = currentTab.text() + "->" + e.name;
                let newTabLink = tabLink + "-" + e.name;
                let tabtemplate = `
                    <li class="">
                        <a href="{link}" data-toggle="tab" parent="{parent}" id="{id}"><div class="delete_sheet"><i class="fa fa-trash" aria-hidden="true"></i></div>{name}<div class="new_sheet"><i class="fa fa-plus" aria-hidden="true"></i></div></a>
                    </li>
                    `;
                tabtemplate = tabtemplate.replace("{link}", newTabLink);
                tabtemplate = tabtemplate.replace("{parent}", e.parentId.toString());
                tabtemplate = tabtemplate.replace("{id}", e.id.toString());
                tabtemplate = tabtemplate.replace("{name}", tabName);
                let newElem = $(tabtemplate);
                currentLi.after(newElem);
                let newSheetTemplate = `
                    <div class="tab-pane " id="{tab-id}">
                        <div class="editor">
                            <div class="editor__inner_container">
                                <table class="sheet_editor" orientation="column" parent="{current-id}" name="{name}" unloaded="true">
                                </table>
                            </div>
                        </div>
                    </div>
                    `;
                newSheetTemplate = newSheetTemplate.replace("{tab-id}",newTabLink.replace("#", ""));
                newSheetTemplate = newSheetTemplate.replace("{current-id}", e.id.toString());
                newSheetTemplate = newSheetTemplate.replace("{name}", e.name);
                $(".tab-content").append(newSheetTemplate);
                newElem.find("a").click();
            })
        });
        e.preventDefault();
        e.stopPropagation();
    }

    protected deleteSheet(e : JQueryEventObject)
    {
        this.deleteConfirmation.show(() => {
            let targetSheet = $(e.target).parents("a").first().attr("href");
            let sheetIdToDelete = parseInt($(targetSheet).find(".sheet_editor").attr("parent"));
            let liElem = $(e.target).parents("li").first();
            /**
             * The element to move to when the current is deleted
             */
            let leftElement = liElem.prev(); // previous is prefered
            if(leftElement.length == 0)
                leftElement = liElem.next(); // if there is no previous then the next tab will be clicked
            if(liElem.hasClass("active"))
                leftElement.find("a").click();

            $(targetSheet).detach();
            liElem.detach();
            TagsApi.deleteTag(sheetIdToDelete);
        });
        e.preventDefault();
        e.stopPropagation();
    }




    /**
     * initializes the editor on the active tab
     */
    protected initialize()
    {
        this.deleteConfirmation = new ConfirmDialog();
        this.newSheetConfirmation = new NewNamedResourceDialog();
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
        console.log("loading new editor");
        let id = $(e.target).attr("href");
        //let id = this.formatId($(e.target).attr("href"));
        let targetSheet = $(id).find(".editor");
        if (this.sheetEditors[id] == undefined)
            this.sheetEditors[id] = new SheetEditor(targetSheet, this.dataBlockEditor, this.tagsEditor);
    }

}
