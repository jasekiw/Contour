import {Serializable} from "../abstract/Serializable";
import {PlainTag} from "../datatag/DataTag";
/**
 * Created by Jason on 12/22/2015.
 */
export class DataBlock implements PlainDataBlock, Serializable
{
    public id : number;
    public value : string;
    public type : string;
    public tags : PlainTag[];
    constructor()
    {

    }

    fromPlainObject(obj : PlainDataBlock) : void
    {
        this.id = obj.id;
        this.type = obj.type;
        this.value = obj.value;
        this.tags =  obj.tags;
    }

    toPlainObject() : PlainDataBlock
    {
        return {id: this.id, value: this.value, type: this.type, tags: this.tags};
    }
}

export interface PlainDataBlock
{
    id : number;
    value : string;
    type : string;
    tags : PlainTag[];
}