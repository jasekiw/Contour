import {DataBlockEditor} from "./DataBlockEditor";
import {PlainTag} from "../data/datatag/DataTag";
import {cellTemplate} from "../ui/templates/Cell";
import {TagsApi} from "../api/TagsApi";
import {SheetsApi} from "../api/SheetsApi";
import {Spinner} from "../ui/Spinner";
import {DatablockSheetHandler} from "./DatablockSheetHandler";
import {DataBlocksApi} from "../api/DataBlocksApi";
import {NewTagDialog} from "../ui/dialogs/NewTagDialog";
import {Ajax} from "../Ajax";
import {mouse} from "../components/MouseHandler";

/**
 * Sheet Editor Class
 */
export class SheetEditor
{
    protected editor : JQuery;
    protected container : JQuery;
    protected addRowTagElement : JQuery;
    public orientation : string;
    protected addColumnTagElement : JQuery;
    protected spinner : Spinner;
    protected datablockManager : DatablockSheetHandler;
    protected element : JQuery;
    public dataBlockEditor : DataBlockEditor;
    protected ajax : Ajax;
    protected newTagDialog : NewTagDialog;
    protected currentText : string;
    /**
     * Constructs the Sheet Editor
     * 
     * @param element The Table element that will be used for the base element
     * @param dataBlockEditor The datablocke editor to open when datablocks need editing
     */
    constructor(element : JQuery, dataBlockEditor : DataBlockEditor)
    {
        this.element = element.find(".sheet_editor");
        this.dataBlockEditor = dataBlockEditor;
        this.ajax = new Ajax();
        this.newTagDialog = new NewTagDialog();
        this.editor = element;
        this.container = this.editor.find(".editor__inner_container");
        this.setupControls();
        this.setup();
        this.datablockManager = new DatablockSheetHandler(this.element, dataBlockEditor, this);
    }



    public getParentId() : number
    {
        return parseInt(this.element.attr("parent"));
    }


    /**
     * Shows the new tag dialog with the specified type
     * @param type
     */
    protected showNewTagDialog(type : string)
    {
        if(this.orientation == "column")
        {
            let lastSortNumber = parseInt(this.element.find("thead .tag_column").last().attr("sort_number"));
            this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), this.getParentId(), lastSortNumber + 1,  type, mouse.x, mouse.y);
        }
        else
        {
            let lastSortNumber = parseInt(this.element.find("tbody .tag_row").last().attr("sort_number"));
            this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), this.getParentId(), lastSortNumber + 1,  type, mouse.x, mouse.y);
        }


    }
    protected setup()
    {
        this.orientation = this.element.attr("orientation");
        this.setupSheetControls();
        this.element.find(".tag").on("remove", (e) => this.handleRemovedTag(e));
        this.element.find(".arrayHandle").on("remove", (e) => this.handleRemovedArray(e));
    

    }

    protected handleRemovedArray(e : JQueryEventObject)
    {
        if (this.orientation == "column")
        {
            $(e.target).off("remove");
            let $header = $(e.target).parents(".tag_row");
            let sort_number = $header.attr("sort_number");
            let rowNumber = $(e.target).parents(".tag_row").prevAll(".tag_row").length;
            let datablockIdsToRemove : number[] = [];
            $(this.element.find("tbody .tag_row").get(rowNumber)).find(".cell").each((index, el) => {
                let $cell = $(el);
                let datablockId = parseInt($cell.find("input").attr("datablock"));
                if(!isNaN(datablockId))
                    datablockIdsToRemove.push(datablockId);
                $cell.remove();
            });
            $header.remove();
            DataBlocksApi.remove(datablockIdsToRemove);
        }
        else
        {
            $(e.target).off("remove");
            let $header = $(e.target).parents(".tag_column");
            let sort_number = $header.attr("sort_number");
            let columnNumber = $(e.target).parents(".tag_column").prevAll(".tag_column").length;
            let datablockIdsToRemove : number[] = [];
            this.element.find("tbody .tag_row").each((index, element) => {
                let $cell = $($(element).find(".cell").get(columnNumber));
                let datablockId = parseInt($cell.find("input").attr("datablock"));
                if(!isNaN(datablockId))
                    datablockIdsToRemove.push(datablockId);
                $cell.remove();
            });
            $header.remove();
            DataBlocksApi.remove(datablockIdsToRemove);
        }

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

        this.spinner = new Spinner(this.editor);
        let changeOrientation = $(`<a title="Change Orientation" href="javascript:void(0);"><i class="fa fa-repeat" aria-hidden="true"></i></a>`);
        let controlsTemplate = $(`<div class="controls"></div>`);
        controlsTemplate.append(changeOrientation);
        this.editor.prepend(controlsTemplate);

        //this.editor.find(".controls").append(changeOrientation);
        changeOrientation.click(() => this.changeOrientation());
    }

    protected changeOrientation()
    {
        let startHeight = this.hideSheetAndLoad();
        let tagId = this.getParentId();
        if(this.orientation == "column")
            this.orientation = "row";
        else
            this.orientation = "column";
        TagsApi.setMeta(tagId, "orientation", this.orientation, () => {
            SheetsApi.get(tagId, (e : string) => {
               this.finishChangingOrientation(startHeight, e);
            });
        });

    }

    protected hideSheetAndLoad() : number
    {
        let startHeight = this.editor.height();
        this.editor.css("height", startHeight);
        this.container.fadeOut({
            duration: 300
        });
        this.spinner.start(300);
        return startHeight
    }

    protected finishChangingOrientation(startHeight : number, e : string)
    {
        this.element.find("*").off("remove");
        this.element.replaceWith($(e));
        this.element = this.editor.find(".sheet_editor");
        this.spinner.stop(300);
        this.setup();
        this.container.fadeIn({
            duration: 300
        });
        this.editor.css('height', 'auto');
        let endHeight = this.editor.height();
        this.editor.css("height", startHeight);
        this.editor.animate({height: endHeight}, {
            duration: 300,
            complete: () => {
                this.editor.css('height', 'auto');
            }
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
                    <div class="sort_number arrayHandle">{sort}</div>
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
        let newElement = $(newRowTemplate);
        this.element.find('tbody .new_row').before(newElement);
        newElement.find(".sort_number").on("remove", (e) => this.handleRemovedArray(e));
    }

    protected handleNewColumnTag(tag : PlainTag)
    {
        let newTag = $(`<th class="tag_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);
        //append the tag column
        this.element.find("thead tr .new_column").before(newTag);
        newTag.on("remove", (e) => this.handleRemovedTag(e));
        // append a new cell for the new column for each row
        this.addColumnCells();
    }


    protected addColumn()
    {
        let lastHeader = this.element.find("thead .tag_column").last();
        let newHeaderTemplate = `
        <th class="tag_column tags" tags="" sort_number="{sort}">
            <div class="tags">
            </div>
            <div class="sort_number arrayHandle">{sort}</div>
        </th>
        `;
        newHeaderTemplate = newHeaderTemplate.replace(new RegExp("{sort}", 'g'), (parseInt(lastHeader.attr("sort_number")) + 1).toString());
        let newElement = $(newHeaderTemplate);
        lastHeader.after(newElement);
        console.log(newElement.find(".sort_number"));
        newElement.find(".sort_number").on("remove", (e) => this.handleRemovedArray(e));
        this.addColumnCells();
    }
    protected addColumnCells()
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
        if(this.orientation == "column")
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
        else
        {
            //TODO: implement for row.
        }


    }

  

}