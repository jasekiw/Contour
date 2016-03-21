/**
 * Created by Jason Gallavin on 12/22/2015.
 */
    //<reference path="references.d.ts />"
class Main
{
    public dataBlockEditor : DataBlockEditor;
    public sheetEditor : SheetEdtior;

    constructor()
    {
        this.dataBlockEditor = new DataBlockEditor();
        this.sheetEditor = new SheetEdtior(this.dataBlockEditor);

    }


}
