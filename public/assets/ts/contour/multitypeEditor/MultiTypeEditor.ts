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
        $(".sheet_editor").each((index, element) => {
            this.sheetEditors.push(new SheetEditor(this.dataBlockEditor, element))
        });

    }

}
