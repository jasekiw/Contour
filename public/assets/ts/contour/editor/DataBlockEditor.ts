import {Ajax} from "../Ajax";
import {BackgroundFilter} from "../ui/BackgroundFilter";
import {PopUpScreen} from "../ui/PopUpScreen";
import {TagsEditor} from "./TagsEditor";
import {DataBlocksApi} from "../api/DataBlocksApi";
import {PlainDataBlock} from "../data/datablock/DataBlock";
import {DataTag, PlainTag} from "../data/datatag/DataTag";
import {IntMap} from "../lib/IntMap";
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
    // the field that holds the formula
    private dataBlockFormula : JQuery;
    private saveButton : JQuery;
    private calculateButton : JQuery;
    private calculationOutputContainer : JQuery;
    private tagsEditor : TagsEditor;
    private parentId : number;
    private datablock : PlainDataBlock;
    private onSave : (block : PlainDataBlock, addedTags?: IntMap<PlainTag>, deletedTags? :  IntMap<PlainTag>) => void;
    private tagsStarted : IntMap<PlainTag>;
    private currentTags : IntMap<PlainTag>;

    constructor(tagsEditor : TagsEditor)
    {
        super("datablock-editor", editorTemplate);
        this.insertElement();
        this.tagsEditor = tagsEditor;
        this.dataBlockFormula = this.element.find("input[name='datablock_value']");
        this.saveButton = this.element.find("input.submit");
        this.calculateButton = this.element.find("input[name='calculate']");
        this.calculationOutputContainer = this.element.find(".calculated");
        this.element.find(".exitButton").click(() => this.exit());
        this.saveButton.on("click", (e) =>
        {
            e.preventDefault();
            this.save();
            this.exit();
        });
        this.element.find(".edit-tags").click((e) => this.editTags());
        this.calculateButton.on("click", (e) => this.calculate());
    }

    /**
     * Calculates the current formula and displays it
     */
    protected calculate()
    {
        (new Ajax()).post("/api/getValue", {
            datablock:   this.dataBlockFormula.val(),
            datablockid: this.datablock.id
        }, (response : {result : string}) =>
        {
            this.calculationOutputContainer.html(response.result);
        });
    }

    /**
     * shows the tag editor for this datablock
     */
    protected editTags()
    {
        let tagIds : number[] = [];
        this.datablock.tags.forEach((tag, i) =>
        {
            tagIds.push(tag.id);
        });
        this._hide();
        this.tagsEditor.show(tagIds, this.parentId, (addedTags, deletedTags) =>
            {
                this.datablock.tags = [];
                addedTags.iterate((i, tag) => this.currentTags.set(tag.id, tag));
                deletedTags.iterate((index, tag) => this.currentTags.remove(tag.id));
                this._show()
            },
            () =>  this._show());

    }

    /**
     * Opens the datablock editor for editing a datablock
     * @param datablockId
     * @param sheetId
     * @param value
     * @param onSave
     */
    public open(datablockId : number, sheetId : number, value : string, onSave? : (block : PlainDataBlock, addedTags?: IntMap<PlainTag>, deletedTags? :  IntMap<PlainTag>) => void) : void
    {
        this.onSave = onSave;

        DataBlocksApi.getById(datablockId, (block) =>
        {
            this.datablock = block;
            this.tagsStarted = new IntMap<PlainTag>();
            block.tags.forEach((tag, i) => this.tagsStarted.set(tag.id, tag));
            this.currentTags = this.tagsStarted.clone();
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

    /**
     * Hides the window
     */
    public exit() : void
    {
        this._hide();
    }

    /**
     * Saves the datablock and calls the callback function with the deleted tags and the added tags
     */
    private save() : void
    {
        this.tagsStarted.diff(this.currentTags, (deletedTags, addedTags) => {
            DataBlocksApi.removeTagsFromDatablock(deletedTags.intKeys(),this.datablock.id);
            DataBlocksApi.addTagsToDatablock(addedTags.intKeys(),this.datablock.id);
            (new Ajax).post("/api/datablocks/save/" + this.datablock.id, {value: this.dataBlockFormula.val()});
            this.datablock.tags = [];
            this.currentTags.iterate((i,tag) => this.datablock.tags.push(tag));
            this.datablock.value = this.dataBlockFormula.val();
            this.onSave(this.datablock, addedTags, deletedTags);
        });
        

    }


}