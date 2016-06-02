import {PlainTag} from "../data/datatag/DataTag";
import {SheetEditor} from "./SheetEditor";
import {SheetCellHandler} from "./SheetCellHandler";
import {cellTemplate} from "../ui/templates/Cell";


export class SheetTagHandler
{

    protected editor : SheetEditor;
    protected cellHandler : SheetCellHandler;

    constructor(editor : SheetEditor, cellHandler : SheetCellHandler)
    {
        this.editor = editor;
        this.cellHandler = cellHandler;
    }


    /**
     * Handles the removal of a column tag.
     * @param target The tag being removed
     */
    protected handleRemovedPrimaryColumnTag(target : JQuery)
    {
        let targetIndex = target.prevAll(".tag").length;
        let collection = $();
        this.editor.element.find("tbody > .tag_row").each((rowIndex, row) => collection =  collection.add($(row).find(".cell").get(targetIndex)));
        this.cellHandler.deleteDatablocks(collection);
    }

    /**
     * Handles a removed tag
     * @param e The event for the removing of the tag
     */
    public handleRemovedPrimaryTag(e : JQueryEventObject)
    {
        console.log("removing");
        let target = $(e.target);
        target.off("remove");
        if(this.editor.orientation == "column")
            this.handleRemovedPrimaryColumnTag(target);
        else
            this.handleRemovedPrimaryRowTag(target);
    }

    /**
     * Adds a primary tag to the columns
     * @param tag
     */
    protected handleNewPrimaryColumnTag(tag : PlainTag)
    {
        let newTag = $(`<th class="tag_column tag" tag="` + tag.id + `">` + tag.name + `</th>`);
        this.editor.element.find("thead tr .new_column").before(newTag);
        newTag.on("remove", (e) => this.handleRemovedPrimaryTag(e));
        this.cellHandler.addColumnCells();
    }


    protected handleNewPrimaryRowTag(tag : PlainTag)
    {
        let newRowTemplate = `
        <tr class="tag_row" sort_number="{sort}" tag="{tag}">
            <td class="row_head">
                <div class="tag primary" tag="{tag}">{tag_name}</div>
            </td>                     
        </tr>
        `;
        newRowTemplate = newRowTemplate.replace(new RegExp("{sort}", 'g'), tag.sort_number.toString());
        newRowTemplate = newRowTemplate.replace(new RegExp("{tag}", 'g'), tag.id.toString());
        newRowTemplate = newRowTemplate.replace(new RegExp("{tag_name}", 'g'), tag.name);
        let newElement = $(newRowTemplate);
        this.editor.element.find(".new_row").before(newElement);
        newElement.find(".tag.primary").on("remove", (e) => this.handleRemovedPrimaryTag(e));
        this.cellHandler.addRowCells();
    }
    protected handleRemovedGenericTag(e : JQueryEventObject)
    {

    }


    /**
     * Handles the creation of a new tag
     * @param tag
     */
    public handleNewTag(tag : PlainTag)
    {
        if (this.editor.orientation == "column") {
            if (tag.type == "primary")
                this.handleNewPrimaryColumnTag(tag);
            else
              this.addNewGenericTag(tag);

        }
        else {
            if (tag.type == "primary")
                this.handleNewPrimaryRowTag(tag);
            else
                this.addNewGenericTag(tag);
        }

    }
    public addNewGenericTag(tag : PlainTag)
    {
        let newTag = $(`<td class="row_name tag" tag="` + tag.id + `">` + tag.name + `</td>`);
        let newWrapper = $(`<tr class="tag_row" tag="` + tag.id + `"></tr>`).append(newTag);
        this.editor.element.find("tbody .new_row").before(newWrapper);
        newTag.on("remove", (e) => this.handleRemovedGenericTag(e));
        let numColumns : number = this.editor.element.find(".tag_column").length;
        let toAdd : string = "";
        for (let i = 0; i < numColumns; i++)
            toAdd += cellTemplate;
        this.editor.element.find("tbody .tag_row").last().append(toAdd);
    }

    /**
     * Handles the removal of a Primary row tag.
     * @param target The tag being removed
     */
    protected handleRemovedPrimaryRowTag(target : JQuery)
    {
        console.log("removing row tags");
        let cells = target.parents(".tag_row").find(".cell");
        this.cellHandler.deleteDatablocks(cells);
        target.parents(".tag_row").remove();
    }

    /**
     * Adds the header Html For a new General Column
     */
    public addGeneralHeaderColumn()
    {
        let lastHeader = this.editor.element.find("thead .tag_column").last();
        let newHeaderTemplate = `
        <th class="tag_column tags" tags="" sort_number="{sort}">
            <div class="tags">
            </div>
            <div class="sort_number GeneralListHandle">{sort}</div>
        </th>
        `;
        newHeaderTemplate = newHeaderTemplate.replace(new RegExp("{sort}", 'g'), (parseInt(lastHeader.attr("sort_number")) + 1).toString());
        let newElement = $(newHeaderTemplate);
        lastHeader.after(newElement);
        console.log(newElement.find(".sort_number"));
        newElement.find(".sort_number").on("remove", (e) => this.editor.handleRemovedGeneralList(e));
    }

    /**
     * Adds the header Html For a new General Row
     */
    public addGeneralHeaderRow()
    {
        let newRowTemplate = `
            <tr class="tag_row" sort_number="{sort}">
                <td class="row_head" tags="">
                    <div class="tags">
                        
                    </div>
                    <div class="sort_number GeneralListHandle">{sort}</div>
                </td>   
           </tr>
            `;
        let lastSortNumber = parseInt(this.editor.element.find('tbody .tag_row').last().attr("sort_number"));
        let newSortNumber = isNaN(lastSortNumber) ? 0 : lastSortNumber + 1;
        newRowTemplate = newRowTemplate.replace(new RegExp("{sort}", 'g'), newSortNumber.toString());
        let newElement = $(newRowTemplate);
        this.editor.element.find('tbody .new_row').before(newElement);
        newElement.find(".sort_number").on("remove", (e) => this.editor.handleRemovedGeneralList(e));
    }
}