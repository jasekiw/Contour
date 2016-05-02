import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {mouse} from "../components/MouseHandler";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";

/**
 * Created by Jason on 1/12/2016.
 */

export class SheetEditor
{
    public dataBlockEditor : DataBlockEditor;
    /**
     * Table element
     */
    private sheet : JQuery;
    private currentText : string;
    private addColumnTagElement : JQuery;
    private addRowTagElement : JQuery;
    private newTagDialog : NewTagDialog;
    constructor(dataBlockEditor : DataBlockEditor, element : Element)
    {
        this.newTagDialog = new NewTagDialog();
        this.sheet = $(element);
        this.dataBlockEditor = dataBlockEditor;
        this.sheet.find(".tag").on("remove", (e : JQueryEventObject) => this.handleRemovedTag(e));
        this.sheet.on("dblclick", ".cell input",(e) => {
            e.preventDefault();
            var thisElement = $(e.currentTarget);
            var excelSheet : number = parseInt(this.sheet.attr("sheet"));
            this.dataBlockEditor.open(thisElement, excelSheet, thisElement.val());
        });
        this.sheet.on("focusin", ".cell input", (e) => {
            var currentElement = $(e.currentTarget);
            this.sheet =  currentElement.parents('.sheet_editor');
            this.sheet.find('.sheet_row').each( (index : number, element : Element) => {
                var $element = $(element);
                $element.removeClass('current_row');
            });
            currentElement.parents('.sheet_row').addClass('current_row');
            console.log(currentElement.parents('.sheet_row'));
            this.currentText = $(e.currentTarget).val();
        });
        this.sheet.on("focusout", ".cell input",(e) => {
            console.log("unfocusing");
            if($(e.currentTarget).val() != this.currentText)
            {
                var datablockId : number = parseInt($(e.currentTarget).attr("datablock"));
                if(isNaN(datablockId)) // if there is no datablock id for this datablock (not created yet)
                {

                }
                else
                {
                    (new Ajax).post("/api/datablocks/save/" + datablockId,{value: $(e.currentTarget).val()}, () => {} );
                }

            }

        });
        this.setupSheetControls();

    }
    private setupSheetControls() {
        var headerRow = this.sheet.find("thead > tr");
        var newColumnTagTemplate =  `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));

        this.addColumnTagElement = headerRow.find(".new_column i");
        var body = this.sheet.find("tbody");
        var newRowTemplate = `<tr class="new_row"><td class="row_name"><i class="fa fa-plus" aria-hidden="true"></i></td></tr>`;
        body.append($(newRowTemplate));
        this.addRowTagElement = body.find(".new_row i");

        this.addColumnTagElement.click(() => this.showNewTagDialog("column"));
        this.addRowTagElement.click(() => this.showNewTagDialog("row"));
        
    }

    private showNewTagDialog(type: string)
    {
        this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), parseInt(this.sheet.attr("sheet")) ,type,mouse.x, mouse.y );
    }

    /**
     * Handles the creation of a new tag
     * @param tag
     */
    private handleNewTag(tag : PlainTag)
    {
        if(tag.type == "column")
        {
            var newTag = $(`<th class="sheet_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);

            //append the tag column
            this.sheet.find("thead tr .new_column").before(newTag);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            // append a new cell for the new column for each row
            this.sheet.find("tbody .sheet_row").each((index, element) => {
                $(element).append(cellTemplate);
            });
        }
        else
        {
            var newTag = $(`<td class="row_name tag" tag="` + tag.id + `">` + tag.name + `</td>`);
            var newWrapper = $(`
            <tr class="sheet_row" tag="` + tag.id + `">
                
            </tr>`).append(newTag);

            this.sheet.find("tbody .new_row").before(newWrapper);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            var numColumns : number = this.sheet.find(".sheet_column").length;
            let toAdd :string = "";
            for(let i = 0; i < numColumns; i++)
                toAdd += cellTemplate;
            this.sheet.find("tbody .sheet_row").last().append(toAdd);
        }
    }
    private handleRemovedTag(e : JQueryEventObject) {
        var target = $(e.target);
        target.off("remove");
        console.log("removing...");
        if(target.hasClass("sheet_column"))
           this.handleRemovedColumnTag(target);
        else
            this.handleRemovedRowTag(target);
    }

    /**
     * Handles the removal of a column tag.
     * @param target The tag being removed
     */
    private handleRemovedColumnTag(target : JQuery) {
        var previousElements = target.prevAll(".tag");
        var targetIndex = target.prevAll(".tag").length;

        this.sheet.find("tbody > .sheet_row").each((rowIndex, row) => {
            $(row).find(".cell").each( ( cellIndex, cell) => {
                if(cellIndex == targetIndex)
                    $(cell).remove();
            });
        });
    }

    /**
     * Handles the removal of a row tag.
     * @param target The tag being removed
     */
    private handleRemovedRowTag(target : JQuery) {
        target.parent().remove();
    }
}