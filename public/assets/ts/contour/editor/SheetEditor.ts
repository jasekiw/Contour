import {DataBlockEditor} from "./DataBlockEditor";
import {PlainTag} from "../data/datatag/DataTag";
import {TagsApi} from "../api/TagsApi";
import {SheetsApi} from "../api/SheetsApi";
import {Spinner} from "../ui/Spinner";
import {SheetCellHandler} from "./SheetCellHandler";
import {NewTagDialog} from "../ui/dialogs/NewTagDialog";
import {Ajax} from "../Ajax";
import {mouse} from "../components/MouseHandler";
import {SheetTagHandler} from "./SheetTagHandler";
import {TagsEditor} from "./TagsEditor";


/**
 * Sheet Editor Class
 */
export class SheetEditor
{

    protected editor : JQuery;
    protected container : JQuery;
    protected addRowTagElement : JQuery;
    protected addColumnTagElement : JQuery;
    protected spinner : Spinner;
    protected cellHandler : SheetCellHandler;
    protected ajax : Ajax;
    protected newTagDialog : NewTagDialog;
    protected currentText : string;
    protected tagHandler : SheetTagHandler;
    protected tagsEditor : TagsEditor;
    public orientation : string;
    public  element : JQuery;
    public dataBlockEditor : DataBlockEditor;

    /**
     * Constructs the Sheet Editor
     *
     * @param element The Table element that will be used for the base element
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     * @param tagsEditor the editor that handles editing tags for rows and datablocks
     */
    constructor(element : JQuery, dataBlockEditor : DataBlockEditor, tagsEditor : TagsEditor)
    {
        this.element = element.find(".sheet_editor");
        this.dataBlockEditor = dataBlockEditor;
        this.tagsEditor = tagsEditor;
        this.ajax = new Ajax();
        this.newTagDialog = new NewTagDialog();
        this.editor = element;
        this.container = this.editor.find(".editor__inner_container");
        this.setupOutsideControls();
        if (this.editor.attr("unloaded") == undefined) {

            let startHeight = this.hideSheet();
            this.showLoader();
            let tagId = this.getParentId();
            SheetsApi.get(tagId, (e : string) =>
            {
                this.loadSheet(startHeight, e);
            });
        }
        else
            this.setup();
    }
    public editTagsOnGeneralList($elem : JQuery)
    {
        
        let $container = $elem.parent();
        let tagIdStrings = $container.attr("tags").split(",");
        let tagIds : number[] = [];
        for(let i =0; i < tagIdStrings.length; i++)
            tagIds.push(parseInt(tagIdStrings[i]))
        this.tagsEditor.show(tagIds,parseInt($container.parents(".sheet_editor").attr("parent")), (tags) => {
            
        });
        

    }






    /**
     * Sets up the editor for the sheet. Needs to be called when the shet is reloaded
     */
    protected setup()
    {
        this.orientation = this.element.attr("orientation");
        this.setupSheetControls();
        this.element.find(".tag.primary").on("remove", (e) => this.tagHandler.handleRemovedPrimaryTag(e));
        this.element.find(".GeneralListHandle").on("remove", (e) => this.handleRemovedGeneralList(e));
        this.cellHandler = new SheetCellHandler(this.element, this.dataBlockEditor, this);
        this.tagHandler = new SheetTagHandler(this, this.cellHandler);

    }

    /**
     * Adds The new tag buttons. is called with setup.
     */
    protected setupSheetControls()
    {
        let headerRow = this.element.find("thead > tr");
        let newColumnTagTemplate = `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));
        this.addColumnTagElement = headerRow.find(".new_column i");
        let tbody = this.element.find("tbody");
        let newRowTemplate = `<tr class="new_row"><td class="row_name"><i class="fa fa-plus" aria-hidden="true"></i></td></tr>`;
        tbody.append($(newRowTemplate));
        this.addRowTagElement = tbody.find(".new_row i");
        this.setupSheetEventHandlers();
    }

    /**
     * Sets up controls that will not ever be removed until page refresh
     */
    protected setupOutsideControls()
    {
        this.spinner = new Spinner(this.editor);
        let changeOrientation = $(`<a title="Change Orientation" href="javascript:void(0);"><i class="fa fa-repeat" aria-hidden="true"></i></a>`);
        let controlsTemplate = $(`<div class="controls"></div>`);
        controlsTemplate.append(changeOrientation);
        this.editor.prepend(controlsTemplate);
        changeOrientation.click(() => this.changeOrientation());
    }

    /**
     * Changes the orientation of the sheet. meta is set and the sheet is reloaded
     */
    protected changeOrientation()
    {
        let startHeight = this.hideSheet();
        this.showLoader();
        let tagId = this.getParentId();
        if (this.orientation == "column")
            this.orientation = "row";
        else
            this.orientation = "column";
        TagsApi.setMeta(tagId, "orientation", this.orientation, () =>
        {
            SheetsApi.get(tagId, (e : string) =>
            {
                this.loadSheet(startHeight, e);
            });
        });

    }

    /**
     * Hides and re
     * @returns {number}
     */
    protected hideSheet() : number
    {
        let startHeight = this.editor.height();
        this.editor.css("height", startHeight);
        this.container.fadeOut({
            duration: 300
        });
        return startHeight
    }

    /**
     * Shows the loader
     */
    protected showLoader()
    {
        this.spinner.start(300);
    }

    /**
     * loads the sheet. animates the height from the start height to the real heigth
     * @param startHeight The height before load
     * @param newSheetHTML The new sheet to show
     */
    protected loadSheet(startHeight : number, newSheetHTML : string)
    {
        this.element.find("*").off("remove");
        this.element.replaceWith($(newSheetHTML));
        this.element = this.editor.find(".sheet_editor");
        this.spinner.stop(300);
        this.setup();
        this.container.fadeIn({
            duration: 300
        });
        this.editor.css('height', 'auto');
        let endHeight = this.editor.height();
        this.editor.css("height", startHeight);
        this.editor.animate({height: endHeight}, {
            duration: 300,
            complete: () =>
                      {
                          this.editor.css('height', 'auto');
                      }
        });
    }

    /**
     * Gets the tag Id for the sheet.
     * @returns {number}
     */
    public getParentId() : number
    {
        return parseInt(this.element.attr("parent"));
    }

    /**
     * Shows the new tag dialog with the specified type
     * @param type
     */
    protected showNewTagDialog(type : string)
    {
        if (this.orientation == "column") {
            let lastSortNumber = parseInt(this.element.find("thead .tag_column").last().attr("sort_number"));
            this.newTagDialog.show((e : PlainTag) => this.tagHandler.handleNewTag(e), this.getParentId(), lastSortNumber + 1, type, mouse.x, mouse.y);
        }
        else {
            let lastSortNumber = parseInt(this.element.find("tbody .tag_row").last().attr("sort_number"));
            this.newTagDialog.show((e : PlainTag) => this.tagHandler.handleNewTag(e), this.getParentId(), lastSortNumber + 1, type, mouse.x, mouse.y);
        }

    }

    /**
     * Sets up the event handlers for the sheet
     */
    protected setupSheetEventHandlers()
    {
        if (this.orientation == "column")
            this.addColumnTagElement.click(() => this.showNewTagDialog("primary"));
        else
            this.addColumnTagElement.click(() => this.addGeneralColumn());
        if (this.orientation == "column")
            this.addRowTagElement.click(() => this.addGeneralRow());
        else
            this.addRowTagElement.click(() => this.showNewTagDialog("primary"));
    }

    /**
     * Adds a general Row
     */
    protected addGeneralRow()
    {
        this.tagHandler.addGeneralHeaderRow();
        this.cellHandler.addRowCells();
    }

    /**
     * Adds a general Column
     */
    protected addGeneralColumn()
    {
        this.tagHandler.addGeneralHeaderColumn();
        this.cellHandler.addColumnCells();
    }

    protected addPrimaryRow()
    {

    }
    protected addPrimaryColumn()
    {

    }

    /**
     * handles the removal of a general list. triggeres by the removing of the dom object .GeneralListHandle
     * @param e
     */
    public handleRemovedGeneralList(e : JQueryEventObject)
    {
        if (this.orientation == "column") {
            $(e.target).off("remove");
            let $header = $(e.target).parents(".tag_row");
            let sort_number = $header.attr("sort_number");
            let rowNumber = $(e.target).parents(".tag_row").prevAll(".tag_row").length;
            let collection = $();
            $(this.element.find("tbody .tag_row").get(rowNumber)).find(".cell").each((index, el) => collection = collection.add($(el)));
            $header.remove();
            this.cellHandler.deleteDatablocks(collection);
        }
        else { // row
            $(e.target).off("remove");
            let $header = $(e.target).parents(".tag_column");
            let sort_number = $header.attr("sort_number");
            let columnNumber = $(e.target).parents(".tag_column").prevAll(".tag_column").length;
            let collection = $();
            this.element.find("tbody .tag_row").each((index, el) => collection = collection.add($(el).find(".cell").get(columnNumber)));
            $header.remove();
            this.cellHandler.deleteDatablocks(collection);
        }

    }



 

    

}