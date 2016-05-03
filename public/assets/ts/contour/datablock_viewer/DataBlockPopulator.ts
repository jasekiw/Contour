import {Ajax} from "../Ajax";
import {PlainDataBlock as DataBlock} from "../data/datablock/DataBlock";
/**
 * Created by Jason on 12/22/2015.
 */

export class DataBlockPopulator
{
    private columns : DataTag[];
    private rows : DataTag[];
    public rowIndex : number = 0;
    private columnIndex : number = 0;
    private ajax : Ajax;

    /**
     * Constructs DataBlockPopulator
     * @param rowTags
     * @param columnTags
     */
    constructor(rowTags : DataTag[], columnTags : DataTag[])
    {
        this.ajax = new Ajax();
        this.rows = rowTags;
        this.columns = columnTags;
    }

    /**
     *
     * @param functionToCall
     */
    public getNextDataBlock(functionToCall : (e : {datablock : DataBlock, success : boolean}) => void) : void
    {
        if (this.rowIndex >= this.rows.length) {
            functionToCall(null);
            return;
        }
        console.log("tags to send");
        console.log({tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id)});
        this.ajax.post("/api/datablocks/get_by_tags", {tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id)}, (e) => functionToCall(<{datablock : DataBlock, success : boolean}>e));
        this.columnIndex++;
        if (this.columnIndex >= this.columns.length) {
            this.columnIndex = 0;
            this.rowIndex++;
        }

    }
}