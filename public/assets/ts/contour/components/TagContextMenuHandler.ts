import {TagsApi} from "../api/Tags";
import {RenameTagDialog} from "../ui/RenameTagDialog";
import {DeleteTagDialog} from "../ui/DeleteTagDialog";
import {AjaxData} from "../Ajax";
/**
 * Created by Jason Gallavin on 4/29/2016.
 */
export class TagContextMenuHandler {
    private renameDialog : RenameTagDialog;
    private deleteDialog : DeleteTagDialog;
    constructor(selector : string)
    {
        this.renameDialog = new RenameTagDialog();
        this.deleteDialog = new DeleteTagDialog();
        $("body").contextmenu({
            target : "#tagContextMenu",
            before: (e, context) => {
                return !e.ctrlKey;

            },
            onItem: (context, e) => this.handleTagContextMenu(context, e),
            scopes: selector
        });
    }


    /**
     * Handles the tag context menu
     * @param context
     * @param e
     */
    protected handleTagContextMenu(context : JQuery, e : JQueryEventObject) {
        var menuItem = $(e.target);
        var action = menuItem.attr("href");

        if(action == "rename")
        {
            this.renameDialog.show((e) => {

                context.text(e.name);
            }, parseInt(context.attr("tag")));
        }
        else if(action == "delete")
        {
            this.deleteDialog.show((e : AjaxData) => {
                if(e.success)
                    context.remove();
                else
                    alert(e.message)
            },parseInt(context.attr("tag")));
        }
    }


}