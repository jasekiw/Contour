import {Serializable} from "../abstract/Serializable";
/**
 * Created by Jason Gallavin on 4/21/2016.
 */
export class DataTag implements PlainTag, Serializable
{
    fromPlainObject(obj : PlainTag) : void
    {
        this.id = obj.id;
        this.name = obj.name;
        this.parentId = obj.parentId;
        this.type = obj.type;
        this.typeId = obj.typeId;
        this.sort_number = obj.sort_number;
    }

    toPlainObject() : PlainTag
    {
        return {name: this.name, id: this.id, typeId: this.typeId, sort_number: this.sort_number, type: this.type, parentId: this.parentId};
    }

    public name : string;
    public id : number;
    public typeId : number;
    public type : string;
    public parentId : number;
    public sort_number : number;

}

export interface PlainTag
{
    name : string;
    id : number;
    typeId : number;
    type : string;
    parentId : number;
    sort_number : number;
}