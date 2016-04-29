import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEditor";
import {template as contextMenuTemplate} from "../ui/templates/TagContextMenu";
import {TagContextMenuHandler} from "../components/TagContextMenuHandler";
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
        $("body").append(contextMenuTemplate);
        
        this.dataBlockEditor = new DataBlockEditor();
        $(".sheet_editor").each((index, element) => {
            this.sheetEditors.push(new SheetEditor(this.dataBlockEditor, element))
        });

    }




}
