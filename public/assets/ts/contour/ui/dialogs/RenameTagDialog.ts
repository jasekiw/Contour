import {DialogBox} from "./Dialog";
import {PlainTag} from "../../data/datatag/DataTag";
import {mouse} from "../../components/MouseHandler";
import {TagsApi} from "../../api/Tags";

var template = `
   <input type="hidden" name="id" value="" />
    <div class="form-group">
        <label>New Name</label>
        <input class="form-control" type="text" name="newName" value="" />
    </div>
`;
export class RenameTagDialog extends DialogBox
{

    constructor()
    {
        super("Rename", "Rename", template, "/api/tags/rename");

    }

    public show(placeHolder : string, onSubmit : (tag : PlainTag) => void, id : number)
    {
        super._show(onSubmit, mouse.x, mouse.y);
        this.form.find('input[name="id"]').val(id);
        this.form.find('input[name="newName"]').val(placeHolder);
        this.element.show();
        console.log("got here");
    }

    protected submit(e : JQueryEventObject)
    {
        e.preventDefault();
        TagsApi.rename(parseInt(this.form.find('input[name="id"]').val()), this.form.find('input[name="newName"]').val(), (e) => this.onSubmit(e));
        this.hide();
    }

}