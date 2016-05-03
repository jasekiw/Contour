import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {DataBlocksApi} from "../api/DataBlocks";
import {PlainTag} from "../data/datatag/DataTag";
import {mouse} from "../components/MouseHandler";
import {cellTemplate} from "../ui/templates/Cell";
/**
 * Created by Jason Gallavin on 5/3/2016.
 */
export class TableEditor
{
    public dataBlockEditor : DataBlockEditor;
    /** Table element */
    private table : JQuery;
    private currentText : string;
    private addColumnTagElement : JQuery;
    private addRowTagElement : JQuery;
    private newTagDialog : NewTagDialog;
    private ajax : Ajax;

    /**
     * Constructs the Table Editor
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     * @param element The Table element that will be used for the base element
     */
    constructor(dataBlockEditor : DataBlockEditor, element : Element)
    {
        this.ajax = new Ajax();
        this.newTagDialog = new NewTagDialog();
        this.table = $(element);
        this.dataBlockEditor = dataBlockEditor;
        this.table.find(".tag").on("remove", (e) => this.handleRemovedTag(e));
        this.table.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.table.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.table.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
        this.setupSheetControls();

    }

    /**
     * Saves the current datablock
     * @param $target The Jquery Element/datablock that needs to be saved
     */
    private saveDataBlock($target : JQuery)
    {
        let value = $target.val();
        if (value != this.currentText) {
            let datablockId : number = parseInt($target.attr("datablock"));
            if (isNaN(datablockId)) // if there is no datablock id for this datablock (not created yet)
                this.createDataBlock($target);
            else
                DataBlocksApi.save(datablockId, value);
        }
    }

    /**
     * Creates a datablock based on the element given
     * @param $element
     */
    private createDataBlock($element : JQuery)
    {
        let columnNumber : number = $element.parents(".cell").prevAll(".cell").length; // number of columns before it
        let rowNumber : number = $element.parents(".sheet_row").prevAll(".sheet_row").length; // nubmer of the rows before it.
        let columnId = this.getColumnTagIdAt(columnNumber);
        let rowId = this.getRowTagIdAt(rowNumber);
        if(isNaN(columnId) || isNaN(rowId))
            console.log("error creating datablock at column:" + columnNumber + " row " + rowNumber);
        else
        {

            DataBlocksApi.create([columnId, rowId], "cell", $element.val(), (block) => {
                $element.attr("datablock", block.id);
                console.log("datablock created with value " + block.value);
            });
        }

    }

    private getColumnTagIdAt(sort : number)
    {
        return parseInt(this.table.find("thead .sheet_column").get(sort).getAttribute("tag"));
    }

    /**
     *
     * @param sort
     * @returns {number}
     */
    private getRowTagIdAt(sort : number)
    {
        return parseInt(this.table.find("tbody tr.sheet_row").get(sort).getAttribute("tag"));
    }

    /**
     * Opens datablock with the datablock editor
     * @param e
     */
    private openDataBlock(e : JQueryEventObject)
    {
        e.preventDefault();
        var $element = $(e.currentTarget);
        var excelSheetId : number = parseInt(this.table.attr("table"));
        this.dataBlockEditor.open($element, excelSheetId, $element.val());
    }

    /**
     * performs ui changes when a datablock is focused
     * @param e
     */
    private focusOnDataBlock(e : JQueryEventObject)
    {
        var currentElement = $(e.currentTarget);
        this.table = currentElement.parents('.sheet_editor');
        this.table.find('.sheet_row').each((index : number, element : Element) =>
        {
            var $element = $(element);
            $element.removeClass('current_row');
        });
        currentElement.parents('.sheet_row').addClass('current_row');
        this.currentText = $(e.currentTarget).val();
    }

    /**
     * Adds The new tag buttons
     */
    private setupSheetControls()
    {
        var headerRow = this.table.find("thead > tr");
        var newColumnTagTemplate = `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));
        this.addColumnTagElement = headerRow.find(".new_column i");
        this.addColumnTagElement.click(() => this.showNewTagDialog("column"));
        console.log("setup controls");
    }

    /**
     * Shows the new tag dialog with the specified type
     * @param type
     */
    private showNewTagDialog(type : string)
    {
        this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), parseInt(this.table.attr("table")), type, mouse.x, mouse.y);
    }

    /**
     * Handles the creation of a new tag
     * @param tag
     */
    private handleNewTag(tag : PlainTag)
    {
        if (tag.type == "column") {
            var newTag = $(`<th class="sheet_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);

            //append the tag column
            this.table.find("thead tr .new_column").before(newTag);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            // append a new cell for the new column for each row
            this.table.find("tbody .sheet_row").each((index, element) =>
            {
                $(element).append(cellTemplate);
            });
        }
        else {
            let newTag = $(`<td class="row_name tag" tag="` + tag.id + `">` + tag.name + `</td>`);
            let newWrapper = $(`<tr class="sheet_row" tag="` + tag.id + `"></tr>`).append(newTag);
            this.table.find("tbody .new_row").before(newWrapper);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            let numColumns : number = this.table.find(".sheet_column").length;
            let toAdd : string = "";
            for (let i = 0; i < numColumns; i++)
                toAdd += cellTemplate;
            this.table.find("tbody .sheet_row").last().append(toAdd);
        }

    }

    /**
     * Handles a removed tag
     * @param e
     */
    private handleRemovedTag(e : JQueryEventObject)
    {
        var target = $(e.target);
        target.off("remove");
        console.log("removing...");
        if (target.hasClass("sheet_column"))
            this.handleRemovedColumnTag(target);
        else
            this.handleRemovedRowTag(target);
    }

    /**
     * Handles the removal of a column tag.
     * @param target The tag being removed
     */
    private handleRemovedColumnTag(target : JQuery)
    {
        var previousElements = target.prevAll(".tag");
        var targetIndex = target.prevAll(".tag").length;

        this.table.find("tbody > .sheet_row").each((rowIndex, row) =>
        {
            $(row).find(".cell").each((cellIndex, cell) =>
            {
                if (cellIndex == targetIndex)
                    $(cell).remove();
            });
        });
    }

    /**
     * Handles the removal of a row tag.
     * @param target The tag being removed
     */
    private handleRemovedRowTag(target : JQuery)
    {
        target.parent().remove();
    }
}