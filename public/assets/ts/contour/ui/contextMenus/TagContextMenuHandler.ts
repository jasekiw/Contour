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
        $.contextMenu(
        {
            // define which elements trigger this menu
            selector: selector,
            // define the elements of the menu
            items:    {
                Rename: {
                    name: "Rename", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {
                        this.renameDialog.show(opt.$trigger.text(), (e) =>
                        {
                            opt.$trigger.text(e.name);
                        }, parseInt(opt.$trigger.attr("tag")));
                    }
                },
                Delete: {
                    name: "Delete", callback: (key, opt : JQueryContextMenuRuntimeOptions) =>
                    {
                        this.deleteDialog.show((e : AjaxResponse) =>
                        {
                            if (e.success)
                                opt.$trigger.remove();
                            else
                                alert(e.message)

                        }, parseInt(opt.$trigger.attr("tag")));
                    }
                }
            },
            build:    ($triggerElement, e) =>
                      {

                          return !e.ctrlKey;

                      },
           className : "green-contextMenu"


        });

        $(selector).on("contextmenu", (e : JQueryEventObject) =>
        {
            return !e.ctrlKey;
        });

    }
}