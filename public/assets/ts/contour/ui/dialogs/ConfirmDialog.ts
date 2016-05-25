/**
 * Created by Jason Gallavin on 5/25/2016.
 */
import {mouse} from "../../components/MouseHandler";
import {GeneralDialogBox} from "./GeneralDialogBox";

export class ConfirmDialog extends GeneralDialogBox
{

    constructor()
    {
        super("Are you sure you want to do this?", "Yes", "");
    }

    public show(onSubmit : (e) => void)
    {
        super._show(onSubmit, mouse.x, mouse.y);
        this.element.show();
    }

    protected submit(e : JQueryEventObject)
    {
        e.preventDefault();
        var id = this.form.find("[name=id]").val();
        this.onSubmit(true);
        this.hide();
    }

}