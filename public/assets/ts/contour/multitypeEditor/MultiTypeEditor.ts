import {DataBlockEditor} from "./DataBlockEditor";
import {SheetEditor} from "./SheetEdtior";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class MultiTypeEditor
{
    public dataBlockEditor : DataBlockEditor;
    public sheetEditor : SheetEditor;

    constructor()
    {
        this.dataBlockEditor = new DataBlockEditor();
        this.sheetEditor = new SheetEditor(this.dataBlockEditor);
    }


}
