import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {PlainTag} from "../data/datatag/PlainTag";
import {mouse} from "../components/MouseHandler";
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
    constructor(dataBlockEditor : DataBlockEditor)
    {
        this.newTagDialog = new NewTagDialog();
        this.sheet = $('.sheet_editor').first();
        this.dataBlockEditor = dataBlockEditor;
        $(".cell input").dblclick((e) => {
            e.preventDefault();
            var thisElement = $(e.currentTarget);
            var excelSheet : number = parseInt($(".excel_editor").attr("sheet"));
            this.dataBlockEditor.open(thisElement, excelSheet, thisElement.val());
        });
        $(".cell input").focusin((e) => {
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
        $(".cell input").focusout((e) => {
            if($(e.currentTarget).val() != this.currentText)
                (new Ajax).post("/api/datablocks/save/" + $(e.currentTarget).attr("datablock"),{value: $(e.currentTarget).val()}, () => {} );
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
    private handleNewTag(tag : PlainTag)
    {
        if(tag.type == "column")
        {
            this.sheet.find("thead tr .new_column").before(`<th class="sheet_column" tag="` + tag.id + `">` + tag.name + `</th>`);
        }
        else
        {
            this.sheet.find("tbody .new_row").before(`
            <tr class="sheet_row" tag="` + tag.id + `">
                <td class="row_name" tag_id="` + tag.id + `">` + tag.name + `</td>
            </tr>`);
        }
    }
}