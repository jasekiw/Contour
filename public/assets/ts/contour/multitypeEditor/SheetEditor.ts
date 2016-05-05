import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {mouse} from "../components/MouseHandler";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";
import {DataBlocksApi} from "../api/DataBlocks";
import {Editor} from "./Editor";
import {TabularEditor} from "./TabularEditor";

/**
 * Sheet Editor Class
 */
export class SheetEditor extends TabularEditor
{

    protected addRowTagElement : JQuery;

    /**
     * Constructs the Sheet Editor
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     * @param element The Table element that will be used for the base element
     */
    constructor(dataBlockEditor : DataBlockEditor, element : Element)
    {
        super($(element), dataBlockEditor);
    }
    
    /**
     *
     * @param sort
     * @returns {number}
     */
    protected getRowTagIdAt(sort : number)
    {
        return parseInt(this.element.find("tbody tr.tag_row").get(sort).getAttribute("tag"));
    }

    /**
     * Adds The new tag buttons
     */
    protected setupSheetControls()
    {
        var headerRow = this.element.find("thead > tr");
        var newColumnTagTemplate = `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));

        this.addColumnTagElement = headerRow.find(".new_column i");
        var body = this.element.find("tbody");
        var newRowTemplate = `<tr class="new_row"><td class="row_name"><i class="fa fa-plus" aria-hidden="true"></i></td></tr>`;
        body.append($(newRowTemplate));
        this.addRowTagElement = body.find(".new_row i");

        this.addColumnTagElement.click(() => this.showNewTagDialog("column"));
        this.addRowTagElement.click(() => this.showNewTagDialog("row"));

    }

    /**
     * Handles the creation of a new tag
     * @param tag
     */
    protected handleNewTag(tag : PlainTag)
    {
        if (tag.type == "column")
            this.handleNewColumnTag(tag);
        else {
            let newTag = $(`<td class="row_name tag" tag="` + tag.id + `">` + tag.name + `</td>`);
            let newWrapper = $(`<tr class="tag_row" tag="` + tag.id + `"></tr>`).append(newTag);
            this.element.find("tbody .new_row").before(newWrapper);
            newTag.on("remove", (e) => this.handleRemovedTag(e));
            let numColumns : number = this.element.find(".tag_column").length;
            let toAdd : string = "";
            for (let i = 0; i < numColumns; i++)
                toAdd += cellTemplate;
            this.element.find("tbody .tag_row").last().append(toAdd);
        }

    }

    /**
     * Creates a datablock based on the element given
     * @param $element
     */
    protected createDataBlock($element : JQuery)
    {
        let columnNumber : number = $element.parents(".cell").prevAll(".cell").length; // number of columns before it
        let rowNumber : number = $element.parents(".tag_row").prevAll(".tag_row").length; // nubmer of the rows before it.
        let columnId = this.getColumnTagIdAt(columnNumber);
        let rowId = this.getRowTagIdAt(rowNumber);
        if (isNaN(columnId) || isNaN(rowId))
            console.log("error creating datablock at column:" + columnNumber + " row " + rowNumber);
        else {

            DataBlocksApi.create([columnId, rowId], "cell", $element.val(), (block) =>
            {
                $element.attr("datablock", block.id);
                console.log("datablock created with value " + block.value);
            });
        }

    }

}