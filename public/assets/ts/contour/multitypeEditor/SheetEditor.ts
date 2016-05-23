import {DataBlockEditor} from "./DataBlockEditor";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";
import {DataBlocksApi} from "../api/DataBlocks";
import {Editor} from "./Editor";
import {TagsApi} from "../api/Tags";
import {SheetsApi} from "../api/SheetsApi";

/**
 * Sheet Editor Class
 */
export class SheetEditor extends Editor
{
    protected editor : JQuery;
    protected addRowTagElement : JQuery;
    protected orientation : string;
    protected addColumnTagElement : JQuery;

    /**
     * Constructs the Sheet Editor
     * 
     * @param element The Table element that will be used for the base element
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     */
    constructor(element : JQuery, dataBlockEditor : DataBlockEditor)
    {
        super(element.find(".sheet_editor"), dataBlockEditor);
        this.editor = element;
        this.setupControls();
        this.setup();


    }
    protected setup()
    {
        this.orientation = this.element.attr("orientation");
        this.setupSheetControls();
        this.element.find(".tag").on("remove", (e) => this.handleRemovedTag(e));
        this.element.on("dblclick", ".cell input", (e) => this.openDataBlock(e));
        this.element.on("focusin", ".cell input", (e) => this.focusOnDataBlock(e));
        this.element.on("focusout", ".cell input", (e) => this.saveDataBlock($(e.currentTarget)));
    }


    /**
     * Adds The new tag buttons
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


        this.setupEventHandlers();
    }

    protected setupControls()
    {
        let changeOrientation = $(`<a title="Change Orientation" href="javascript:void(0);"><i class="fa fa-repeat" aria-hidden="true"></i></a>`);
        let controlsTemplate = $(`<div class="controls"></div>`);
        controlsTemplate.append(changeOrientation);
        this.editor.prepend(controlsTemplate);
        //this.editor.find(".controls").append(changeOrientation);
        changeOrientation.click(() => this.changeOrientation());
    }

    protected changeOrientation()
    {
        console.log("changing orientation...");
        let tagId = this.getParentId();
        if(this.orientation == "column")
            this.orientation = "row";
        else
            this.orientation = "column";

        TagsApi.setMeta(tagId, "orientation", this.orientation, () => {
            SheetsApi.get(tagId, (e : string) => {
                this.element.find(".tag").off("remove");
                this.element.replaceWith($(e));
                this.element = this.editor.find(".sheet_editor");
                this.setup();
            });
        });

    }


    protected setupEventHandlers()
    {
        if(this.orientation == "column")
            this.addColumnTagElement.click(() => this.showNewTagDialog("primary"));
        else
            this.addColumnTagElement.click(() => this.addColumn());
        if(this.orientation == "column")
            this.addRowTagElement.click(() => this.addRow());
        else
            this.addRowTagElement.click(() => this.showNewTagDialog("primary"));
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
     * performs ui changes when a datablock is focused
     * @param e
     */
    protected focusOnDataBlock(e : JQueryEventObject)
    {
        let currentElement = $(e.currentTarget);
        this.element.find('.tag_row').each((index : number, element : Element) =>
        {
            let $element = $(element);
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
        let previousElements = target.prevAll(".tag");
        let targetIndex = target.prevAll(".tag").length;

        this.element.find("tbody > .tag_row").each((rowIndex, row) =>
        {
            $(row).find(".cell").each((cellIndex, cell) =>
            {
                if (cellIndex == targetIndex)
                    $(cell).remove();
            });
        });
    }



    /**
     * Handles a removed tag
     * @param e
     */
    protected handleRemovedTag(e : JQueryEventObject)
    {
        let target = $(e.target);
        target.off("remove");
        console.log("removing...");
        if (target.hasClass("tag_column"))
            this.handleRemovedColumnTag(target);
        else
            this.handleRemovedRowTag(target);
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



    protected addRow()
    {
        let columns = this.element.find('thead .tag_column').length;

        let cellTemplate = `
         <td class="cell">
                <input type="text" class="form-control input-sm" value="">
         </td>    
        `;

        let newRowTemplate = `
            <tr class="tag_row" sort_number="{sort}">
                <td class="row_head" tags="">
                    <div class="tags">
                        
                    </div>
                    <div class="sort_number">{sort}</div>
                </td>
                {cells}
           </tr>
            `;
        let cellsToAdd = "";
        for(let i = 0; i < columns; i++)
            cellsToAdd += cellTemplate;
        let lastSortNumber = parseInt(this.element.find('tbody .tag_row').last().attr("sort_number"));
        if(isNaN(lastSortNumber))
            newRowTemplate = newRowTemplate.replace(new RegExp("{sort}", 'g'), "0");
        else
            newRowTemplate = newRowTemplate.replace(new RegExp("{sort}", 'g'), (lastSortNumber + 1).toString());

        newRowTemplate = newRowTemplate.replace("{cells}", cellsToAdd);
        this.element.find('tbody .new_row').before(newRowTemplate);
    }

    protected handleNewColumnTag(tag : PlainTag)
    {
        let newTag = $(`<th class="tag_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);
        //append the tag column
        this.element.find("thead tr .new_column").before(newTag);
        newTag.on("remove", (e) => this.handleRemovedTag(e));
        // append a new cell for the new column for each row
        this.addColumn();
    }


    protected addColumn()
    {
        this.element.find("tbody .tag_row").each((index, element) =>
        {
            $(element).append(cellTemplate);
        });
    }

    /**
     * Handles the creation of a new tag
     * @param tag
     */
    protected handleNewTag(tag : PlainTag)
    {
        if (tag.type == "primary")
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