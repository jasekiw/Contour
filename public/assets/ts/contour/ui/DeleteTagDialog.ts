import {DialogBox} from "./Dialog";
import {mouse} from "../components/MouseHandler";
import {TagsApi} from "../api/Tags";
var template = `
<input type="hidden" name="id" value="" />
`;
export class DeleteTagDialog extends DialogBox
{

    constructor()
    {
        super("Delete Tag", "Delete", template, "/api/tag/delete");
    }

    public show(onSubmit : (e) => void, id : number)
    {
        super._show(onSubmit, mouse.x, mouse.y);
        this.form.find("[name=id]").val(id);
        this.element.show();
    }

    protected submit(e : JQueryEventObject)
    {
        e.preventDefault();
        var id = this.form.find("[name=id]").val();
        TagsApi.deleteTag(id, (e) => this.onSubmit(e));
        this.hide();
    }

}