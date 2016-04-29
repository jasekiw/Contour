import {Serializable} from "../abstract/Serializable";
/**
 * Created by Jason Gallavin on 4/21/2016.
 */
export class DataTag implements PlainTag, Serializable{
    fromPlainObject(obj: PlainTag):void {
        this.id = obj.id;
        this.name = obj.name;
        this.parentId = obj.parentId;
        this.type = obj.type;
        this.typeId = obj.typeId;
    }
    toPlainObject(): PlainTag {
        return { name: this.name, id: this.id, typeId: this.typeId, type: this.type, parentId: this.parentId};
    }
    public name : string;
    public id : number;
    public typeId : number;
    public type : string;
    public parentId : number;

}

export interface PlainTag {
    name : string;
    id : number;
    typeId : number;
    type : string;
    parentId : number;
}