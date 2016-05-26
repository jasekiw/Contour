import {RenameTagDialog} from "./../dialogs/RenameTagDialog";
import {DeleteTagDialog} from "./../dialogs/DeleteTagDialog";
import {AjaxResponse} from "../../Ajax";

export class TagContextMenuHandler
{

    private renameDialog : RenameTagDialog;
    private deleteDialog : DeleteTagDialog;

    constructor(selector : string)
    {
        this.renameDialog = new RenameTagDialog();
        this.deleteDialog = new DeleteTagDialog();
        $.contextMenu({
            // define which elements trigger this menu
            selector: selector,
            // define the elements of the menu
            items:    {
                Rename: {
                    name: "Rename", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {
                        var target = opt.$trigger;
                        this.renameDialog.show(opt.$trigger.text(), (e) =>
                        {
                            target.text(e.name);
                        }, parseInt(target.attr("tag")));
                    }
                },
                Delete: {
                    name: "Delete", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {
                        var target = opt.$trigger;
                        this.deleteDialog.show((e : AjaxResponse) =>
                        {
                            if (e.success)
                                target.remove();
                            else
                                alert(e.message)

                        }, parseInt(target.attr("tag")));
                    }
                }
            }
        });



    }
}