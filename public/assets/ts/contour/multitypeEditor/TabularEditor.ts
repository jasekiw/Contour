import {Editor} from "./Editor";
import {DataBlockEditor} from "./DataBlockEditor";
import {DataBlocksApi} from "../api/DataBlocks";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";
/**
 * Created by Jason on 5/4/2016.
 */
export abstract class TabularEditor extends Editor
{

    protected addColumnTagElement : JQuery;

    constructor(element : JQuery, dataBlockEditor : DataBlockEditor)
    {
        super(element, dataBlockEditor);
        this.setupSheetControls();
        this.element.find(".tag").on("remove", (e) => this.handleRemovedTag(e));
        this.element.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.element.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.element.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
    }

    protected getColumnTagIdAt(sort : number)
    {
        return parseInt(this.element.find("thead .tag_column").get(sort).getAttribute("tag"));
    }

    /**
     * Saves the current datablock
     * @param $target The Jquery Element/datablock that needs to be saved
     */
    protected saveDataBlock($target : JQuery)
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
    protected abstract createDataBlock($element : JQuery);

    protected abstract setupSheetControls();

    /**
     * performs ui changes when a datablock is focused
     * @param e
     */
    protected focusOnDataBlock(e : JQueryEventObject)
    {
        var currentElement = $(e.currentTarget);
        this.element.find('.tag_row').each((index : number, element : Element) =>
        {
            var $element = $(element);
            $element.removeClass('current_row');
        });
        currentElement.parents('.tag_row').addClass('current_row');
        this.currentText = $(e.currentTarget).val();
    }

    /**
     * Handles the removal of a row tag.
     * @param target The tag being removed
     */
    protected handleRemovedRowTag(target : JQuery)
    {
        target.parent().remove();
    }

    /**
     * Handles the removal of a column tag.
     * @param target The tag being removed
     */
    protected handleRemovedColumnTag(target : JQuery)
    {
        var previousElements = target.prevAll(".tag");
        var targetIndex = target.prevAll(".tag").length;

        this.element.find("tbody > .tag_row").each((rowIndex, row) =>
        {
            $(row).find(".cell").each((cellIndex, cell) =>
            {
                if (cellIndex == targetIndex)
                    $(cell).remove();
            });
        });
    }

    protected handleNewColumnTag(tag : PlainTag)
    {
        var newTag = $(`<th class="tag_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);

        //append the tag column
        this.element.find("thead tr .new_column").before(newTag);
        newTag.on("remove", (e) => this.handleRemovedTag(e));
        // append a new cell for the new column for each row
        this.element.find("tbody .tag_row").each((index, element) =>
        {
            $(element).append(cellTemplate);
        });
    }

    /**
     * Handles a removed tag
     * @param e
     */
    protected handleRemovedTag(e : JQueryEventObject)
    {
        var target = $(e.target);
        target.off("remove");
        console.log("removing...");
        if (target.hasClass("tag_column"))
            this.handleRemovedColumnTag(target);
        else
            this.handleRemovedRowTag(target);
    }

}