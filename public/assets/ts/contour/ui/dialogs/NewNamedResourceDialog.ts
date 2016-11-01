/**
 * Created by Jason Gallavin on 5/25/2016.
 */
import {mouse} from "../../components/MouseHandler";
import {GeneralDialogBox} from "./GeneralDialogBox";

export class NewNamedResourceDialog extends GeneralDialogBox
{

    constructor()
    {
        super("Add new Sheet", "Create", `  
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="" />
        </div>`);

        this.form.find('input[name="name"]').on("keydown",(event) => {
            console.log("key up! keycode: " + event.keyCode);
            if(event.keyCode == 13) // enter
                this.form.submit();
        });

    }

    public show(onSubmit : (name : string) => void, startingName? : string)
    {
        super._show(onSubmit, mouse.x, mouse.y);
        this.form.find('input[name="name"]').focus();
        if(startingName != undefined)
            this.form.find("[name=name]").val(startingName);
        this.element.show();
    }

    protected submit(e : JQueryEventObject)
    {
        e.preventDefault();
        var name = this.form.find("[name=name]").val();
        this.onSubmit(name);
        this.hide();
    }

}