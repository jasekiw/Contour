import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {TagContextMenuHandler} from "../ui/contextMenus/TagContextMenuHandler";
import {ListContextMenu} from "../ui/contextMenus/ListContextMenu";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class MultiTypeEditor
{
    public dataBlockEditor : DataBlockEditor;
    public sheetEditors : SheetEditor[] = [];
    private tagContextMenuHandler : TagContextMenuHandler;
    private listContextMenu : ListContextMenu;

    constructor()
    {
        this.tagContextMenuHandler = new TagContextMenuHandler(".tag");
        this.listContextMenu = new ListContextMenu(".arrayHandle");

        $(".editor").each((index, element) =>
        {
            if ($(element).find(".sheet_editor").length != 0)
                this.sheetEditors.push(new SheetEditor($(element), this.dataBlockEditor))
        });

    }

}
