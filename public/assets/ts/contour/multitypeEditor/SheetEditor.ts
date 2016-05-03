import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {mouse} from "../components/MouseHandler";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";
import {DataBlocksApi} from "../api/DataBlocks";

/**
 * Sheet Editor Class
 */
export class SheetEditor
{
    public dataBlockEditor : DataBlockEditor;
    /** Table element */
    private sheet : JQuery;
    private currentText : string;
    private addColumnTagElement : JQuery;
    private addRowTagElement : JQuery;
    private newTagDialog : NewTagDialog;
    private ajax : Ajax;

    /**
     * Constructs the Sheet Editor
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     * @param element The Table element that will be used for the base element
     */
    constructor(dataBlockEditor : DataBlockEditor, element : Element)
    {
        this.ajax = new Ajax();
        this.newTagDialog = new NewTagDialog();
        this.sheet = $(element);
        this.dataBlockEditor = dataBlockEditor;
        this.sheet.find(".tag").on("remove", (e) => this.handleRemovedTag(e));
        this.sheet.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.sheet.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.sheet.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
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
        return parseInt(this.sheet.find("thead .sheet_column").get(sort).getAttribute("tag"));
    }

    /**
     *
     * @param sort
     * @returns {number}
     */
    private getRowTagIdAt(sort : number)
    {
       return parseInt(this.sheet.find("tbody tr.sheet_row").get(sort).getAttribute("tag"));
    }

    /**
     * Opens datablock with the datablock editor
     * @param e
     */
    private openDataBlock(e : JQueryEventObject)
    {
        e.preventDefault();
        var $element = $(e.currentTarget);
        var excelSheetId : number = parseInt(this.sheet.attr("sheet"));
        this.dataBlockEditor.open($element, excelSheetId, $element.val());
    }

    /**
     * performs ui changes when a datablock is focused
     * @param e
     */
    private focusOnDataBlock(e : JQueryEventObject)
    {
        var currentElement = $(e.currentTarget);
        this.sheet = currentElement.parents('.sheet_editor');
        this.sheet.find('.sheet_row').each((index : number, element : Element) =>
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
        var headerRow = this.sheet.find("thead > tr");
        var newColumnTagTemplate = `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));

        this.addColumnTagElement = headerRow.find(".new_column i");
        var body = this.sheet.find("tbody");
        var newRowTemplate = `<tr class="new_row"><td class="row_name"><i class="fa fa-plus" aria-hidden="true"></i></td></tr>`;
        body.append($(newRowTemplate));
        this.addRowTagElement = body.find(".new_row i");

        this.addColumnTagElement.click(() => this.showNewTagDialog("column"));
        this.addRowTagElement.click(() => this.showNewTagDialog("row"));

    }

    /**
     * Shows the new tag dialog with the specified type
     * @param type
     */
    private showNewTagDialog(type : string)
    {
        this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), parseInt(this.sheet.attr("sheet")), type, mouse.x, mouse.y);
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
            this.sheet.find("thead tr .new_column").before(newTag);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            // append a new cell for the new column for each row
            this.sheet.find("tbody .sheet_row").each((index, element) =>
            {
                $(element).append(cellTemplate);
            });
        }
        else {
            let newTag = $(`<td class="row_name tag" tag="` + tag.id + `">` + tag.name + `</td>`);
            let newWrapper = $(`<tr class="sheet_row" tag="` + tag.id + `"></tr>`).append(newTag);
            this.sheet.find("tbody .new_row").before(newWrapper);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            let numColumns : number = this.sheet.find(".sheet_column").length;
            let toAdd : string = "";
            for (let i = 0; i < numColumns; i++)
                toAdd += cellTemplate;
            this.sheet.find("tbody .sheet_row").last().append(toAdd);
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

        this.sheet.find("tbody > .sheet_row").each((rowIndex, row) =>
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