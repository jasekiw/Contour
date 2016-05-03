import {Serializable} from "../abstract/Serializable";

/**
 * Created by Jason Gallavin on 4/21/2016.
 */
export class DataType implements PlainType, Serializable
{
    fromPlainObject(obj : PlainType) : void
    {
        this.category_id = obj.category_id;
        this.id = obj.id;
        this.name = obj.name;
    }

    public name : string;
    public category_id : number;
    public id : number;

    toPlainObject() : PlainType
    {
        return {name: this.name, category_id: this.category_id, id: this.id};
    }
}

export interface PlainType
{
    name : string;
    category_id : number;
    id : number;
}