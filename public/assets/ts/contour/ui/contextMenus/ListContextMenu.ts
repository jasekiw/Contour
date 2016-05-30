import {ConfirmDialog} from "../dialogs/ConfirmDialog";
/**
 * Created by Jason on 5/24/2016.
 */

export class ListContextMenu
{
    protected confirmDialog : ConfirmDialog;
    protected editTagsFunction : (elem : JQuery) => void;

    constructor(selector : string)
    {
        this.confirmDialog = new ConfirmDialog();
        $.contextMenu({
            // define which elements trigger this menu
            selector: selector,
            // define the elements of the menu
            items:    {
                editTags: {
                    name: "Edit Tags", callback: (key, opt : JQueryContextMenuRuntimeOptions) => this.editTags(key, opt)},
                Delete:   {
                    name: "Delete", callback: (key, opt : JQueryContextMenuRuntimeOptions) => this.deleteList(key, opt)}
            }

        });
    }

    public setEditTagsFunction(fn : (elem : JQuery) => void)
    {
        this.editTagsFunction = fn;
    }
    protected editTags(key, opt : JQueryContextMenuRuntimeOptions)
    {
        var target = opt.$trigger;
        if(this.editTagsFunction !== undefined)
            this.editTagsFunction(target);
    }
    protected deleteList(key, opt : JQueryContextMenuRuntimeOptions)
    {
        var target = opt.$trigger;
        this.confirmDialog.show((e) => {
            target.remove();
        });
    }

}