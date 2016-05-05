import {DataBlockEditor} from "./DataBlockEditor";
import {Ajax} from "../Ajax";
import {NewTagDialog} from "../ui/NewTagDialog";
import {PlainTag} from "../data/datatag/DataTag";
import {mouse} from "../components/MouseHandler";
/**
 * Created by Jason on 5/4/2016.
 */
export abstract class Editor
{
    protected element : JQuery;
    public dataBlockEditor : DataBlockEditor;
    protected ajax : Ajax;
    protected newTagDialog : NewTagDialog;
    protected currentText : string;
    constructor(element : JQuery, dataBlockEditor : DataBlockEditor)
    {
        this.element = element;
        this.dataBlockEditor = dataBlockEditor;
        this.ajax = new Ajax();
        this.newTagDialog = new NewTagDialog();
    }

    public getParentId() : number
    {
        return parseInt(this.element.attr("parent"));
    }

    /**
     * Opens datablock with the datablock editor
     * @param e
     */
    protected openDataBlock(e : JQueryEventObject)
    {
        e.preventDefault();
        var $element = $(e.currentTarget);
        this.dataBlockEditor.open($element, this.getParentId(), $element.val());
    }
    /**
     * Shows the new tag dialog with the specified type
     * @param type
     */
    protected showNewTagDialog(type : string)
    {
        this.newTagDialog.show((e : PlainTag) => this.handleNewTag(e), this.getParentId(), type, mouse.x, mouse.y);
    }

    protected abstract handleNewTag(e : PlainTag);
}