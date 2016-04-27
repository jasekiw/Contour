/**
 * Created by Jason Gallavin on 4/21/2016.
 */
import {template as NewTagTemplate, style} from "./templates/NewTagDialogTemplate";
import {Types, AjaxTagArrayReponse} from "../api/Types";
import {PlainType} from "../data/type/PlainType";
import {TagsApi} from "../api/Tags";
import {PlainTag} from "../data/datatag/PlainTag";
/**
 * The dialog used to create a new tag
 */
export class NewTagDialog {
    
    private id : string;
    private element : JQuery;
    private form : JQuery;
    private onSubmit : (PlainTag) => void;
    constructor() {
        this.id =  "createTag_" + (new Date().getTime().toString());
        var template = NewTagTemplate.replace("[id]", () : string => { return this.getId() } );
        $("body").append(template);
        $("body").append(style);
        this.element = $("#" + this.id);
        this.form = this.element.find("form");
        this.form.find('[type="submit"]').click( (e) => this.submit(e));
        $('.create_ui form .cancel').click( (e) =>
        {
            e.preventDefault();
            this.hide();
        });

}

    /**
     *
     * @param onSubmit
     * @param parentId
     * @param types
     * @param x
     * @param y
     */
    public show(onSubmit : (tag :PlainTag) => void, parentId: number, types : string = "all",x : number, y: number)
    {
        this.onSubmit = onSubmit;

        this.form.find('input[name="parent_id"]').val( parentId );
        this.form.find('input[name="name"]').val("");
        if(types == "all")
            this.populateTypes();
        else
        {
            this.form.find('select[name="type"]').hide();
            this.form.find('select[name="type"]').html(this.getTypeOption(types))
        }
        var middleX = $(window).width() / 2;
        var middleY = $(window).height() / 2;
        this.element.css("right",'');
        this.element.css("left",'');
        this.element.css("top",'');
        this.element.css("bottom",'');
        this.element.css('position', 'absolute');
        if(x > middleX)
            this.element.css("right",$(window).width() - x);
        else
            this.element.css("left",x);
        if(y > middleY)
            this.element.css("bottom", $(window).height() - y);
        else
            this.element.css("top",  y);

        this.element.show();
    }

    public getId()
    {
        return this.id;
    }

    /**
     * grabs all tag types and puts them into the select menu
     */
    private populateTypes() : void {

        Types.getTagTypes((e : AjaxTagArrayReponse) => {
            var selectOptions : string = "";
            e.payload.forEach((type : PlainType) => {
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
    private getTypeOption(type: string) {
        return  `<option value="` + type + `" >` + type + `</option>\r\n`;
    }

    /**
     * Called when the user submits the form. The Method onSubmit will be called with the tag data to be handled outside this class
     * @param {JQueryEventObject} e
     */
    private submit(e : JQueryEventObject) {
        e.preventDefault();
        var url = this.form.attr("action");
        var name =this.form.find('[name="name"]').val();
        var parentId = parseInt(this.form.find('[name="parent_id"]').val());
        var type = this.form.find('[name="type"]').val();
        TagsApi.create(name,parentId, type, this.onSubmit);
        this.hide();
    }
    public hide() {
        this.element.hide();
    }

}