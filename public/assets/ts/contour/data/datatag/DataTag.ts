import {Serializable} from "../abstract/Serializable";
import {HTMLConvertible} from "../html/HTMLConvertible";
import {JQueryConvertible} from "../html/JQueryConvertible";
/**
 * Created by Jason Gallavin on 4/21/2016.
 */
export class DataTag implements PlainTag, Serializable, HTMLConvertible, JQueryConvertible
{
    public toJQuery() : JQuery
    {
        return $(this.toHTML());
    }
    
    public toHTML() : string
    {
        let tagTemplate = `
            <div class="tag" tag="{id}" sort_number="{sort}" type="{type}" name="{name}" typeId="{type_id}" parentId="{parent_id}">
                {name}
            </div>
            `;
        tagTemplate = tagTemplate.replace("{id}", this.id.toString());
        tagTemplate = tagTemplate.replace("{sort}", this.sort_number.toString());
        tagTemplate = tagTemplate.replace("{name}", this.name);
        tagTemplate = tagTemplate.replace("{name}", this.name);
        tagTemplate = tagTemplate.replace("{type}", this.type);
        tagTemplate = tagTemplate.replace("{type_id}", this.typeId.toString());
        tagTemplate = tagTemplate.replace("{parent_id}", this.parentId.toString());
        return tagTemplate;
    }
    public fromPlainObject(obj : PlainTag) : void
    {
        this.id = obj.id;
        this.name = obj.name;
        this.parentId = obj.parentId;
        this.type = obj.type;
        this.typeId = obj.typeId;
        this.sort_number = obj.sort_number;
    }

    public toPlainObject() : PlainTag
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

