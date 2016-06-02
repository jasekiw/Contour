import {Ajax} from "../Ajax";
import {BackgroundFilter} from "../ui/BackgroundFilter";
import {PopUpScreen} from "../ui/PopUpScreen";
import {TagsEditor} from "./TagsEditor";
import {DataBlocksApi} from "../api/DataBlocksApi";
import {PlainDataBlock} from "../data/datablock/DataBlock";
import {DataTag} from "../data/datatag/DataTag";
var $body = $('body');
var editorTemplate =
    `
<div class="panel panel-default datablock_editor popup" id="{id}">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Block</h3>
        <a class="exitButton" href="javascript:void(0);" ><i class="fa fa-times"></i></a>
    </div>
    <div class="panel-body">
        <div class="top_section">
            <input type="text" name="datablock_value"/>
            <input type="button" name="calculate" value="Calculate" />

        </div>
        <div class="bottom_section">
            <div class="calculated">
            </div>
            <div class="options">
                <input type="submit" value="Save" class="submit" />
            </div>
            <div style="clear:both">
                <button class="evergreen_button edit-tags">Edit Tags</button>
            </div>
            
        </div>
    </div>
</div>
`;

/**
 * Created by Jason Gallavin on 12/22/2015.
 */

export class DataBlockEditor extends PopUpScreen
{
    private dataBlockFormula : JQuery;
    private dataBlockContainer : JQuery;
    private head : JQuery;
    private body : JQuery;
    private searchBar : JQuery;
    private cell : JQuery;
    private saveButton : JQuery;
    private cancelButton : JQuery;
    private calculateButton : JQuery;
    private calculationOutputContainer : JQuery;
    private tagsEditor : TagsEditor;
    private parentId : number;
    private datablock : PlainDataBlock;
    private onSave : (block : PlainDataBlock) => void;

    constructor(tagsEditor : TagsEditor)
    {
        super("datablock-editor", editorTemplate);

        this.insertElement();
        this.tagsEditor = tagsEditor;
        this.dataBlockFormula = this.element.find("input[name='datablock_value']");
        this.dataBlockContainer = this.element.find(".datablocks");
        this.head = this.element.find(".header_container");
        this.body = this.element.find(".row_and_datablock_container");
        this.searchBar = this.element.find("[name=search]");
        this.saveButton = this.element.find("input.submit");
        this.cancelButton = this.element.find("input.cancel");
        this.calculateButton = this.element.find("input[name='calculate']");
        this.calculationOutputContainer = this.element.find(".calculated");
        this.searchBar.change(() =>
        {
            this.filterView(this.searchBar.val());
        });

        this.element.find(".exitButton").click(() => this.exit());

        this.saveButton.on("click", (e) =>
        {
            e.preventDefault();
            this.save();
            this.exit();
        });
        this.element.find(".edit-tags").click((e) =>
        {
            let tagIds : number[] = [];
            this.datablock.tags.forEach((tag, i) =>
            {
                tagIds.push(tag.id);
            });
            this._hide();
            this.tagsEditor.show(tagIds, this.parentId, (tags) =>
                {
                    this.datablock.tags = [];
                    tags.iterate((i, tag) => {
                        this.datablock.tags.push(tag);
                    });
                    this._show()
                },
                () =>  this._show())
        });
        this.cancelButton.on("click", (e) =>
        {
            e.preventDefault();
            this.exit();
        });
        this.calculateButton.on("click", (e) =>
        {
            (new Ajax()).post("/api/getValue", {
                datablock:   this.dataBlockFormula.val(),
                datablockid: this.cell.attr("datablock")
            }, (response : {result : string}) =>
            {
                this.calculationOutputContainer.html(response.result);
            });
        });
    }

    /**
     * Clears the the datablocks our from the container
     */
    private clearDatablocks() : void
    {
        this.dataBlockContainer.html("");
    }

    private addDataBlock(data : string) : void
    {
        this.body.append(data);
    }

    private addtoHead(data : string) : void
    {
        this.head.append(data);
    }

    private removeDatablocks() : void
    {
        this.body.find(".datablock").remove();
    }

    private removeHeadTags() : void
    {
        this.head.html("");
    }

    private removeRowTags() : void
    {
        this.body.find(".rowTag").remove();
    }

    private filterView(filterText : string) : void
    {
        console.log("filtering for" + filterText);
    }

    public open(cell : JQuery, sheetId : number, value : string, onSave? : (block : PlainDataBlock) => void) : void
    {
        this.onSave = onSave;
        this.cell = cell;
        DataBlocksApi.getById(parseInt(this.cell.attr("datablock")), (block) =>
        {
            this.datablock = block;
            this.show(sheetId, value)
        });
    }

    /**
     * Show the editor with the current sheet it
     * @param id The sheet id
     * @param value The value of the cell
     */
    private show(id : number, value : string) : void
    {
        this.parentId = id;
        this.dataBlockFormula.val(value);
        this.calculationOutputContainer.html("");
        this._show();
    }

    public exit() : void
    {
        this._hide();
    }

    private save() : void
    {
        (new Ajax).post("/api/datablocks/save/" + this.cell.attr("datablock"), {value: this.dataBlockFormula.val()}, () =>
        {
        });
        this.cell.val(this.dataBlockFormula.val());
        this.datablock.value = this.dataBlockFormula.val();
        this.onSave(this.datablock);
    }

    /**
     *
     * @param data
     */
    private populateEditor(data : {success : boolean, tags : DataTag[]}) : void
    {

        if (data.success) {
            this.removeHeadTags();
            this.removeRowTags();

            data.tags.forEach((element : { id : number, name : string,  sort_number : number, type : string }, index : number) =>
            {
                var dataToAppend = "<tr class='rowTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' ><td class='tag'>" + element.name + "</td></tr>";

                if (element.type.toUpperCase() == "COLUMN") {
                    this.head.append("<th class='headTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' >" + element.name + "</th>");
                }
                else if (element.type.toUpperCase() == "ROW") // the element to add is a row tag
                {
                    //var numberOfRows = this.body.find(".rowTag").length; // get number of rows

                    if (this.body.find(".rowTag").length > 0) // if there are rows
                    {

                        if (parseInt(this.body.find(".rowTag").last().attr("sort_number")) < element.sort_number)
                            this.body.find(".rowTag").last().after(dataToAppend);
                        else if (parseInt(this.body.find(".rowTag").first().attr("sort_number")) > element.sort_number)
                            this.body.find(".rowTag").last().before(dataToAppend);
                        else {
                            setTimeout(() =>
                            {
                                this.cicleThroughandAdd(".rowTag", dataToAppend, element);
                            }, 1); //fake multithreading
                        }

                    }
                    else {
                        this.body.append(dataToAppend);
                    }

                }

            });

        }
        else {
            window.alert("something went wrong when pupulating the editor");
        }
    }

    private cicleThroughandAdd(classtoLoop : string, dataToAppend : string, element) : void
    {
        var numberOfRows = this.body.find(".rowTag").length;

        this.body.find(classtoLoop).each((index : number) => //loops through each row
        {
            var sortNumber = parseInt($(this).attr("sort_number"));
            if ((numberOfRows - 1) > index && sortNumber > element.sort_number) // if not last and row's sort is grater than element to add
            {

                $(this).before(dataToAppend);
                return false;

            }
            else if ((numberOfRows - 1) == index) {

                if (sortNumber > element.sortNumber)// if row's sort number is greater than the one to add
                {
                    $(this).before(dataToAppend);
                    return false;
                }
                else {
                    $(this).after(dataToAppend);
                    return false;
                }

            }

        });
    }
}