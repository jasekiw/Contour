import {DataBlocksApi} from "../api/DataBlocks";
import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
/**
 * Created by Jason Gallavin on 5/24/2016.
 */
export class DatablockSheetHandler
{
    protected sheet : JQuery;
    protected currentText : string;
    protected dataBlockEditor : DataBlockEditor;
    protected sheetEditor : SheetEditor;


    constructor($sheet : JQuery, datablockEditor : DataBlockEditor, sheetEditor : SheetEditor)
    {
        this.sheet = $sheet;
        this.dataBlockEditor = datablockEditor;
        this.sheetEditor = sheetEditor;
        this.sheet.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.sheet.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.sheet.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
    }


    /**
     * Creates a datablock based on the element given
     * @param $element
     */
    protected createDataBlock($element : JQuery)
    {

        if(this.sheetEditor.orientation == "column")
        {
            let sort_number = parseInt($element.parents(".tag_row").attr("sort_number"));
            let tagIdStrings = $element.parents(".tag_row").find(".row_head").attr("tags").split(/,/);
            let tagIds = [];
            for(let i =0; i < tagIdStrings.length; i++)
                if(!isNaN(parseInt(tagIdStrings[i])))
                    tagIds.push(parseInt(tagIdStrings[i]));


            let columnId : number = this.getColumnTagIdAt($element.parents(".cell").prevAll(".cell").length); // number of columns before it
            tagIds.push(columnId);
            if(tagIds.length == 0)
            {
                console.log("no tags to tag the datablock with. aborting block creation.");
                return;
            }
            DataBlocksApi.create(tagIds, "cell", $element.val(), sort_number, (block) =>
            {
                $element.attr("datablock", block.id);
                console.log("datablock created with value " + block.value);
            });

        }
        else
        {
            let columnNumber = $element.parents(".cell").prevAll(".cell").length;
            let $column = $(this.sheet.find("thead .tag_column").get(columnNumber));
            let tagIdStrings = $column.attr("tags").split(/,/);
            let tagIds = [];
            for(let i =0; i < tagIdStrings.length; i++)
                if(!isNaN(parseInt(tagIdStrings[i])))
                    tagIds.push(parseInt(tagIdStrings[i]));
            let sort_number = parseInt($column.attr("sort_number"));

            let rowId : number = this.getRowTagIdAt($element.parents(".tag_row").prevAll(".tag_row").length); // nubmer of the rows before it.
            tagIds.push(rowId);
            DataBlocksApi.create(tagIds, "cell", $element.val(), sort_number, (block) =>
            {
                $element.attr("datablock", block.id);
                console.log("datablock created with value " + block.value);
            });
        }
        console.log("creating datablock...");


        //
        //if (isNaN(columnId) || isNaN(rowId))
        //    console.log("error creating datablock at column:" + columnNumber + " row " + rowNumber);
        //else {
        //
        //    DataBlocksApi.create([columnId, rowId], "cell", $element.val(), (block) =>
        //    {
        //        $element.attr("datablock", block.id);
        //        console.log("datablock created with value " + block.value);
        //    });
        //}

    }

    protected getColumnTagIdAt(sort : number)
    {
        return parseInt(this.sheet.find("thead .tag_column").get(sort).getAttribute("tag"));
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
     * performs ui changes when a datablock is focused
     * @param e
     */
    protected focusOnDataBlock(e : JQueryEventObject)
    {
        let currentElement = $(e.currentTarget);
        this.sheet.find('.tag_row').each((index : number, element : Element) =>
        {
            let $element = $(element);
            $element.removeClass('current_row');
        });
        currentElement.parents('.tag_row').addClass('current_row');
        this.currentText = $(e.currentTarget).val();
    }

    /**
     * Opens datablock with the datablock editor
     * @param e
     */
    protected openDataBlock(e : JQueryEventObject)
    {
        e.preventDefault();
        var $element = $(e.currentTarget);
        this.dataBlockEditor.open($element, this.sheetEditor.getParentId(), $element.val());
    }

    /**
     *
     * @param sort
     * @returns {number}
     */
    protected getRowTagIdAt(sort : number)
    {
        return parseInt(this.sheet.find("tbody tr.tag_row").get(sort).getAttribute("tag"));
    }
}