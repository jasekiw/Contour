import {Serializable} from "../abstract/Serializable";
/**
 * Created by Jason on 12/22/2015.
 */
export class DataBlock implements PlainDataBlock, Serializable
{
    fromPlainObject(obj: PlainDataBlock):void {
        this.id = obj.id;
        this.type = obj.type;
        this.value = obj.value;
    }

    toPlainObject(): PlainDataBlock {
        return { id : this.id, value: this.value, type: this.type};
    }
    public id :number;
    public value : string;
    public type: string;
    constructor()
    {

    }
}
export interface PlainDataBlock {
     id :number;
     value : string;
     type: string;
}