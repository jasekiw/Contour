import {DataBlocksApi} from "../api/DataBlocksApi";
import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {cellTemplate} from "../ui/templates/Cell";

export class SheetCellHandler
{
    protected currentText : string;
    protected dataBlockEditor : DataBlockEditor;
    protected editor : SheetEditor;


    constructor($sheet : JQuery, datablockEditor : DataBlockEditor, sheetEditor : SheetEditor)
    {
        this.dataBlockEditor = datablockEditor;
        this.editor = sheetEditor;
        this.editor.element.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.editor.element.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.editor.element.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
    }


    /**
     * Creates a datablock based on the element given
     * @param $element
     */
    protected createDataBlock($element : JQuery)
    {

        if(this.editor.orientation == "column")
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
            let $column = $(this.editor.element.find("thead .tag_column").get(columnNumber));
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
        return parseInt(this.editor.element.find("thead .tag_column").get(sort).getAttribute("tag"));
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
        this.editor.element.find('.tag_row').each((index : number, element : Element) =>
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
        this.dataBlockEditor.open($element, this.editor.getParentId(), $element.val());
    }

    /**
     * Deletes datablocks from database and from the screen.
     * @param elements The elements to remove and delete
     */
    public deleteDatablocks(elements : JQuery)
    {
        let datablockIdsToRemove : number[] = [];
        elements.each((index, elem) =>
        {
            let $cell = $(elem);
            let datablockId = parseInt($cell.find("input").attr("datablock"));
            if(!isNaN(datablockId))
                datablockIdsToRemove.push(datablockId);
            $cell.remove();
        });
        
        DataBlocksApi.remove(datablockIdsToRemove);
    }

    /**
     *
     * @param sort
     * @returns {number}
     */
    protected getRowTagIdAt(sort : number)
    {
        return parseInt(this.editor.element.find("tbody tr.tag_row").get(sort).getAttribute("tag"));
    }

    public addColumnCells()
    {
        this.editor.element.find("tbody .tag_row").each((index, element) =>
        {
            $(element).append(cellTemplate);
        });
    }
    public addRowCells()
    {
        let columns = this.editor.element.find('thead .tag_column').length;
        let cellTemplate = `
         <td class="cell">
                <input type="text" class="form-control input-sm" value="">
         </td>    
        `;
        let cellsToAdd = "";
        for (let i = 0; i < columns; i++)
            cellsToAdd += cellTemplate;
        this.editor.element.find("tbody tr.tag_row").last().append($(cellsToAdd));
    }
}