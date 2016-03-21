/**
 * Created by Jason on 1/12/2016.
 */
//<reference path="references.d.ts />"
class SheetEdtior
{
    public dataBlockEditor : DataBlockEditor;
    private sheet : JQuery;
    private currentText : string;
    constructor(dataBlockEditor : DataBlockEditor)
    {
        this.sheet = $('.sheet_editor').first();
        this.dataBlockEditor = dataBlockEditor;
        $(".cell input").dblclick((e) => {
            e.preventDefault();
            var thisElement = $(e.currentTarget);
            var excelSheet : number = parseInt($(".excel_editor").attr("sheet"));
            this.dataBlockEditor.open(thisElement, excelSheet, thisElement.val());
        });
        $(".cell input").focusin((e) => {
            var currentElement = $(e.currentTarget);
            this.sheet =  currentElement.parents('.sheet_editor');
            this.sheet.find('.sheet_row').each( (index :number, element : Element) => {
                var $element = $(element);
                $element.removeClass('current_row');
            });
            currentElement.parents('.sheet_row').addClass('current_row');
            console.log(currentElement.parents('.sheet_row'));
            this.currentText = $(e.currentTarget).val();
        });
        $(".cell input").focusout((e) => {
            if($(e.currentTarget).val() != this.currentText)
            {
                (new Ajax).post("/api/datablocks/save/" + $(e.currentTarget).attr("datablock"),{value: $(e.currentTarget).val()}, () => {} );
            }

        });
    }
}