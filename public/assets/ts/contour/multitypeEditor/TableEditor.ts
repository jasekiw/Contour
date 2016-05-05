import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {DataBlocksApi} from "../api/DataBlocks";
import {PlainTag} from "../data/datatag/DataTag";
import {mouse} from "../components/MouseHandler";
import {cellTemplate} from "../ui/templates/Cell";
import {Editor} from "./Editor";
import {TabularEditor} from "./TabularEditor";
/**
 * Created by Jason Gallavin on 5/3/2016.
 */
export class TableEditor extends TabularEditor
{
    /**
     * Constructs the Table Editor
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     * @param element The Table element that will be used for the base element
     */
    constructor(dataBlockEditor : DataBlockEditor, element : Element)
    {
        super($(element), dataBlockEditor);
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
        if (isNaN(columnId))
            console.log("error creating datablock at column:" + columnNumber + " row " + rowNumber);
        else {

            DataBlocksApi.create([columnId], "cell", $element.val(), (block) =>
            {
                $element.attr("datablock", block.id);
                console.log("datablock created with value " + block.value);
            });
        }

    }

    protected setupSheetControls()
    {
        var headerRow = this.element.find("thead > tr");
        var newColumnTagTemplate = `<th class="new_column"><i class="fa fa-plus" aria-hidden="true"></i></th>`;
        headerRow.append($(newColumnTagTemplate));
        this.addColumnTagElement = headerRow.find(".new_column i");
        this.addColumnTagElement.click(() => this.showNewTagDialog("table-cell"));
    }

    /**
     * Handles the creation of a new tag
     * @param tag
     */
    protected handleNewTag(tag : PlainTag)
    {
        this.handleNewColumnTag(tag);
    }

}