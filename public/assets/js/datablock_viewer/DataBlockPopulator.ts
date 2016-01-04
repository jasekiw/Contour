/**
 * Created by Jason on 12/22/2015.
 */
    ///<reference path="references.d.ts" />
class DataBlockPopulator
{
    private columns : DataTag[];
    private rows : DataTag[];
    public rowIndex : number = 0;
    private columnIndex : number = 0;
    constructor(rowTags : DataTag[], columnTags : DataTag[])
    {
        this.rows = rowTags;
        this.columns = columnTags;
    }
    public getNextDataBlock( functionToCall : (e : {datablock: DataBlock, success: boolean}) => void) : void
    {
        if(this.rowIndex >= this.rows.length)
        {
            functionToCall(null);
            return;
        }
        console.log("tags to send");
        console.log({tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id) });
        (new Ajax).post("/api/datablocks/get_by_tags", {tags: Array(this.rows[this.rowIndex].id, this.columns[this.columnIndex].id) }, (e) => functionToCall(e) );

        this.columnIndex++;
        if(this.columnIndex >= this.columns.length)
        {
            this.columnIndex = 0;
            this.rowIndex++;
        }

    }
}