/**
 * Created by Jason Gallavin on 4/21/2016.
 */
import {content} from "./../templates/NewTagDialogTemplate";
import {Types, AjaxTagArrayReponse} from "../../api/Types";
import {TagsApi} from "../../api/TagsApi";
import {PlainTag} from "../../data/datatag/DataTag";
import {PlainType} from "../../data/type/DataType";
import {DialogBox} from "./Dialog";

/**
 * The dialog used to create a new tag
 */
export class NewTagDialog extends DialogBox
{
    protected sortNumber : number;

    constructor()
    {
        super("Create", "Create", content, "/tags/create");
    }

    /**
     * Shows the dialog to create a new tag
     * @param onSubmit
     * @param parentId
     * @param sort_number
     * @param types
     * @param x
     * @param y
     */
    public show(onSubmit : (tag : PlainTag) => void, parentId : number, sort_number : number, types : string = "all", x : number, y : number)
    {
        this.sortNumber = sort_number;
        super._show(onSubmit, x, y);
        this.form.find('input[name="parent_id"]').val(parentId);
        this.form.find('input[name="name"]').val("");
        if (types == "all")
            this.populateTypes();
        else {
            this.form.find('select[name="type"]').hide();
            this.form.find('select[name="type"]').html(this.getTypeOption(types))
        }
        this.element.show();
    }

    /**
     * grabs all tag types and puts them into the select menu
     */
    private populateTypes() : void
    {

        Types.getTagTypes((e : AjaxTagArrayReponse) =>
        {
            var selectOptions : string = "";
            e.payload.forEach((type : PlainType) =>
            {
                selectOptions += `<option value="` + type.name + `" >` + type.name + `</option>\r\n`;
            });
            this.form.find('select[name="type"]').html(selectOptions);
        });
    }

    /**
     * returns a string of select options of the givenh string
     * @param {string} type
     * @returns {string}
     */
    private getTypeOption(type : string)
    {
        return `<option value="` + type + `" >` + type + `</option>\r\n`;
    }

    /**
     * Called when the user submits the form. The Method onSubmit will be called with the tag data to be handled outside this class
     * @param {JQueryEventObject} e
     */
    protected submit(e : JQueryEventObject)
    {
        e.preventDefault();
        var url = this.form.attr("action");
        var name = this.form.find('[name="name"]').val();
        var parentId = parseInt(this.form.find('[name="parent_id"]').val());
        var type = this.form.find('[name="type"]').val();
        TagsApi.create(name, parentId, this.sortNumber, type, this.onSubmit);
        this.hide();
    }

    public hide()
    {
        this.element.hide();
    }

}