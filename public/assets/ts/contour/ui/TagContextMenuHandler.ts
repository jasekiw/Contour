import {RenameTagDialog} from "./RenameTagDialog";
import {DeleteTagDialog} from "./DeleteTagDialog";
import {AjaxData} from "../Ajax";
import {UIElement} from "./UIElement";

var template = `
<div id="{id}">
	<ul class="dropdown-menu" role="menu">
		<li>
			<a class="context_menu_item" tabindex="-1" href="rename">rename</a>
		</li>
		<li>
			<a class="context_menu_item" tabindex="-1" href="delete">delete</a>
		</li>
	</ul>
</div>
`;

export class TagContextMenuHandler extends UIElement
{

    private renameDialog : RenameTagDialog;
    private deleteDialog : DeleteTagDialog;

    constructor(selector : string)
    {
        super("tagContextMenu", template);
        this.insertElement();
        this.renameDialog = new RenameTagDialog();
        this.deleteDialog = new DeleteTagDialog();
        $("body").contextmenu({
            target: "#" + this.id,
            before: (e, context) =>
                    {
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
    protected handleTagContextMenu(context : JQuery, e : JQueryEventObject)
    {
        var menuItem = $(e.target);
        var action = menuItem.attr("href");

        if (action == "rename") {
            this.renameDialog.show((e) =>
            {
                context.text(e.name);
            }, parseInt(context.attr("tag")));
        }
        else if (action == "delete") {
            this.deleteDialog.show((e : AjaxData) =>
            {
                if (e.success)
                    context.remove();
                else
                    alert(e.message)

            }, parseInt(context.attr("tag")));
        }
    }

}