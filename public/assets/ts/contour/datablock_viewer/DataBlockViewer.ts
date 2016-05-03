import {DataBlockPopulator} from "./DataBlockPopulator";
import {Ajax} from "../Ajax";
import {PlainDataBlock as DataBlock} from "../data/datablock/DataBlock";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */

export class DataBlockViewer
{
    private excelViewerContainer : JQuery;
    private header : JQuery;
    private body : JQuery;
    private columns : DataTag[];
    private rows : DataTag[];
    private populator : DataBlockPopulator;
    private currentRow : number = 0;

    constructor(id : number)
    {
        this.excelViewerContainer = $(".excel_viewer_container");
        this.columns = [];
        this.rows = [];
        (new Ajax()).get("/api/tags/get_children_recursive/" + id, (e : {success : boolean, tags : DataTag[]}) =>
        {
            if (!e.success)
                console.log("error has occurred when getting children");
            else {
                e.tags.forEach((tag : DataTag, index : number) =>
                {
                    console.log(tag);
                    if (tag.type == "row")
                        this.rows.push(tag);
                    else if (tag.type == "column")
                        this.columns.push(tag);

                });
                this.populateTableTags();
            }
        });
    }

    private populateTableTags() : void
    {
        this.excelViewerContainer.append('<table class="excel_viewer"><thead></thead><tbody></tbody></table>');
        this.header = $('.excel_viewer > thead');
        this.body = $('.excel_viewer > tbody');
        this.header.append('<th></th>');
        this.columns.forEach((tag : DataTag, index : number)=>
        {
            this.header.append('<th tag="' + tag.id + '">' + tag.name + '</th>');
        });
        this.rows.forEach((tag : DataTag, index : number)=>
        {
            this.body.append('<tr tag="' + tag.id + '"><td class="column_name_container">' + tag.name + '</td></tr>');
        });
        this.populateTableDataBlocks();

    }

    private populateTableDataBlocks() : void
    {
        this.populator = new DataBlockPopulator(this.rows, this.columns);
        this.populator.getNextDataBlock((e) => this.addDataBlock(e));
    }

    private addDataBlock(e : {datablock : DataBlock, success : boolean}) : void
    {
        if (e.success) {
            if (e.datablock == null)
                return;
            console.log(e.datablock);
            this.body.find('tr').eq(this.currentRow).append('<td class="cell">' + e.datablock.value + '</td>');
        }
        else {
            this.body.find('tr').eq(this.currentRow).append('<td class="cell">' + '</td>');
        }
        this.currentRow = this.populator.rowIndex; // gets the row index before it advances
        this.populator.getNextDataBlock((e) => this.addDataBlock(e));
    }
}