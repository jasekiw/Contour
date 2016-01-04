/**
 * Created by Jason Gallavin on 12/22/2015.
 */
    //<reference path="references.d.ts />"
class Main
{
    public dataBlockEditor : DataBlockEditor;
    constructor()
    {
        this.dataBlockEditor = new DataBlockEditor();

        $(".cell input").dblclick((e) => {
            e.preventDefault();
            var thisElement = $(e.currentTarget);
            var excelSheet : number = parseInt($(".excel_editor").attr("sheet"));
            this.dataBlockEditor.open(thisElement, excelSheet, thisElement.val());
        });
    }


}
