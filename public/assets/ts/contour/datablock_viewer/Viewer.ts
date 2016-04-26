import {DataBlockViewer} from "./DataBlockViewer";
/**
 * Created by Jason Gallavin on 12/22/2015.
 */
export class Viewer
{
    public dataBlockviewer : DataBlockViewer;
    constructor()
    {
        var sheet : number =  parseInt($(".excel_viewer_container").attr("sheet"));
        this.dataBlockviewer = new DataBlockViewer(sheet);
    }
}