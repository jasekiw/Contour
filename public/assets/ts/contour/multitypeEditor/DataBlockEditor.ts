import {Ajax} from "../Ajax";
import {DataBlockInterfacer} from "./DataBlockInterfacer";
import {DataTagInterfacer} from "./DataTagInterfacer";
import {template as editorTemplate} from "./templates/DatablockEditorTemplate";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */

export class DataBlockEditor
{
    private datablockInterfacer : DataBlockInterfacer;
    private dataTagInterfacer : DataTagInterfacer;
    private dataBlockEditor : JQuery;
    private dataBlockFormula : JQuery;
    private dataBlockContainer : JQuery;
    private head : JQuery;
    private body : JQuery;
    private searchBar : JQuery;
    private cell :JQuery;
    private saveButton : JQuery;
    private cancelButton : JQuery;
    private backGroundFilter : JQuery;
    private calculateButton : JQuery;
    private calculationOutputContainer : JQuery;
    constructor()
    {
        $('body').append($(editorTemplate));
        this.datablockInterfacer = new DataBlockInterfacer();
        this.dataTagInterfacer = new DataTagInterfacer();

        this.dataBlockEditor = $("#DatablockEditor");
        this.dataBlockFormula = this.dataBlockEditor.find("input[name='datablock_value']");
        this.dataBlockContainer = this.dataBlockEditor.find(".datablocks");
        this.head = this.dataBlockEditor.find(".header_container");
        this.body = this.dataBlockEditor.find(".row_and_datablock_container");
        this.searchBar = this.dataBlockEditor.find("[name=search]");
        this.saveButton = this.dataBlockEditor.find("input.submit");
        this.cancelButton = this.dataBlockEditor.find("input.cancel");
        this.calculateButton = this.dataBlockEditor.find("input[name='calculate']");
        this.calculationOutputContainer = this.dataBlockEditor.find(".calculated");
        this.searchBar.change( () => {
            this.filterView(this.searchBar.val());
        });
        $("body").append('<div class="background_filter"></div>');
        this.backGroundFilter = $(".background_filter");
        this.dataBlockEditor.find(".exitButton").click(() => {
            this.exit();
        });



        
        this.backGroundFilter.css("background", "black")
            .css("display", "none")
            .css("top", 0)
            .css("bottom", "0")
            .css("left", "0")
            .css("right", "0")
            .css("position", "fixed")
            .css("opacity", "0")
            .css("z-index", "8000");
        this.saveButton.on("click", (e) => {
            e.preventDefault();
            this.save();
            this.exit();
        });

        this.cancelButton.on("click", (e) => {
            e.preventDefault();
            this.exit();
        });
        this.calculateButton.on("click", (e) => {
            (new Ajax()).post("/api/getValue", {datablock: this.dataBlockFormula.val(), datablockid: this.cell.attr("datablock")}, (response : {result : string}) => {
                this.calculationOutputContainer.html(response.result);
            });
        });
    }


    /**
     * Clears the the datablocks our from the container
     */
    private clearDatablocks() : void
    {
        this.dataBlockContainer.html("");
    }

    private addDataBlock(data : string) : void
    {
        this.body.append(data);
    }

    private addtoHead(data: string) : void
    {
        this.head.append(data);
    }

    private removeDatablocks() : void
    {
        this.body.find(".datablock").remove();
    }

    private removeHeadTags() : void
    {
        this.head.html("");
    }

    private removeRowTags() : void
    {
        this.body.find(".rowTag").remove();
    }

    private filterView(filterText : string) : void
    {
        console.log("filtering for" + filterText);
    }

    public open(cell : JQuery, sheetId : number, value : string) : void
    {
        this.cell = cell;
        this.show(sheetId, value)
    }
    /**
     * Show the editor with the current sheet it
     * @param id
     * @param value
     */
    private show (id : number, value : string) : void
    {
        //DataTagInterfacer.getChildrenRecursive(id, (e) => this.populateEditor(e) );
        this.dataBlockFormula.val(value);
        this.calculationOutputContainer.html("");
        this.dataBlockEditor.css("opacity", "0");

        this.dataBlockEditor.show();
        var offsetWidth : number  = window.innerWidth / 2;
        var offsetHeight : number = window.innerHeight / 2;
        $("body").css("max-height", "100%").css("overflow-y", "hidden");
        this.dataBlockEditor.css("position", "fixed");
        this.dataBlockEditor.css("left",10 + "px");
        this.dataBlockEditor.css("top", 10 + "px");
        this.dataBlockEditor.css("bottom", 10 + "px");
        this.dataBlockEditor.css("right", 10 + "px").css("z-index", "9001");
        this.backGroundFilter.show();
        this.backGroundFilter.animate(
            {
                opacity: 0.4
            }, 500
        );
        this.dataBlockEditor.animate({
            opacity: 1
        }, 300)
    }
    public exit() : void
    {
        this.backGroundFilter.animate(
            {
                opacity: 0
            }, 300, () => {
                this.backGroundFilter.hide();
            }
        );
        this.dataBlockEditor.animate({
            opacity: 0
        }, 300, () => {
            this.dataBlockEditor.hide();
        });

        $("body").css("max-height", "").css("overflow-y", "");
    }
    private save() : void
    {
        (new Ajax).post("/api/datablocks/save/" + this.cell.attr("datablock"),{value: this.dataBlockFormula.val()}, () => {} );
        this.cell.val(this.dataBlockFormula.val());
    }


    /**
     *
     * @param data
     */
    private populateEditor (data : {success: boolean, tags : DataTag[]}) : void
    {

        //var thisfunction = this;
        console.log(data);
        console.debug(JSON.stringify(data));

        if(data.success)
        {
            this.removeHeadTags();
            this.removeRowTags();

            data.tags.forEach( ( element : { id : number, name : string,  sort_number: number, type : string }, index : number) =>
            {
                var dataToAppend = "<tr class='rowTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' ><td class='tag'>" + element.name +  "</td></tr>";


                if(element.type.toUpperCase() == "COLUMN")
                {
                    this.head.append("<th class='headTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' >" + element.name +  "</th>");
                }
                else if (element.type.toUpperCase() == "ROW") // the element to add is a row tag
                {
                    //var numberOfRows = this.body.find(".rowTag").length; // get number of rows

                    if(this.body.find(".rowTag").length > 0) // if there are rows
                    {

                        if( parseInt(this.body.find(".rowTag").last().attr("sort_number")) < element.sort_number)
                            this.body.find(".rowTag").last().after(dataToAppend);
                        else if(parseInt(this.body.find(".rowTag").first().attr("sort_number")) > element.sort_number)
                            this.body.find(".rowTag").last().before(dataToAppend);
                        else
                        {
                            setTimeout(() => {
                                this.cicleThroughandAdd(".rowTag",dataToAppend, element );
                            }, 1); //fake multithreading
                        }

                    }
                    else
                    {
                        this.body.append(dataToAppend);
                    }

                }

            });

        }
        else
        {
            window.alert("something went wrong when pupulating the editor");
        }
    }



    private cicleThroughandAdd (classtoLoop : string,dataToAppend : string,  element) : void
    {
        var numberOfRows = this.body.find(".rowTag").length;

        this.body.find(classtoLoop).each((index : number) => //loops through each row
        {
            var sortNumber =  parseInt($(this).attr("sort_number"));
            if((numberOfRows - 1) > index && sortNumber > element.sort_number) // if not last and row's sort is grater than element to add
            {

                $(this).before(dataToAppend);
                return false;

            }
            else if((numberOfRows - 1) == index)
            {

                if(sortNumber > element.sortNumber)// if row's sort number is greater than the one to add
                {
                    $(this).before(dataToAppend);
                    return false;
                }
                else
                {
                    $(this).after(dataToAppend);
                    return false;
                }

            }

        });
    }
}