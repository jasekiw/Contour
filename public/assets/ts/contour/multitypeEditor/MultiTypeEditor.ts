import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {TagContextMenuHandler} from "../ui/TagContextMenuHandler";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class MultiTypeEditor
{
    public dataBlockEditor : DataBlockEditor;
    public sheetEditors : SheetEditor[] = [];
    private tagContextMenuHandler : TagContextMenuHandler;

    constructor()
    {
        this.tagContextMenuHandler = new TagContextMenuHandler(".tag");
        this.dataBlockEditor = new DataBlockEditor();
        $(".editor").each((index, element) => {
            if($(element).find(".sheet_editor").length != 0)
                this.sheetEditors.push(new SheetEditor($(element), this.dataBlockEditor))
        });

    }

}
