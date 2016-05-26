import {ConfirmDialog} from "../dialogs/ConfirmDialog";
/**
 * Created by Jason on 5/24/2016.
 */

export class ListContextMenu
{
    protected confirmDialog : ConfirmDialog;

    constructor(selector : string)
    {
        this.confirmDialog = new ConfirmDialog();
        $.contextMenu({
            // define which elements trigger this menu
            selector: selector,
            // define the elements of the menu
            items:    {
                editTags: {
                    name: "Edit Tags", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {

                    }
                },
                Delete:   {
                    name: "Delete", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {
                        var target = opt.$trigger;
                        console.log(target);
                        this.confirmDialog.show((e) => {
                            console.log(target);
                            target.remove();
                        });
                    }
                }
            }

        });



    }

}