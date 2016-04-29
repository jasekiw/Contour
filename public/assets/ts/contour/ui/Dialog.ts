/**
 * Created by Jason Gallavin on 4/28/2016.
 */
import {template, style} from "./templates/DialogTemplate";
export abstract class DialogBox {
    private template :string;
    private id : string;
    protected element : JQuery;
    protected form : JQuery;
    protected onSubmit : ( response :{}) => void;
    private visible = false;
    constructor(title : string, submitText : string, content: string, action : string) {
        this.id =  "dialog__" + (new Date().getTime().toString());
        this.template = template;
        this.insertTemplate("id", this.id );
        this.insertTemplate("title", title );
        this.insertTemplate("submitText", submitText);
        this.insertTemplate("content", content);
        this.insertTemplate("action", action);
        var $body = $("body");
        $body.append(style);
        $body.append(this.template);
        this.element = $("#" + this.id);
        
        this.element.draggable({
            containment: [10,10,
                $("body").width() - this.element.width() - 30,
                $("body").height() - this.element.height() - 50]
        });
        this.form = this.element.find("form");
        this.form.find('[type="submit"]').click( (e) => this.submit(e));
        this.element.find('form .cancel').click( (e) =>
        {
            e.preventDefault();
            this.hide();
        });
        this.element.click((e : JQueryEventObject) => {
            e.stopPropagation();
        });
        $("body").click((e : JQueryEventObject) => {

            if(this.isShown() && !this.visible)
                this.visible = true;
            else {
                this.visible =false;
                this.hide();
            }
        });
    }

    /**
     *
     * @param onSubmit
     * @param x
     * @param y
     */
    protected _show(onSubmit : (response :{}) => void, x : number, y: number)
    {
        this.onSubmit = onSubmit;
        var middleX = $(window).width() / 2;
        var middleY = $(window).height() / 2;
        this.element.css("right",'');
        this.element.css("left",'');
        this.element.css("top",'');
        this.element.css("bottom",'');
        this.element.css('position', 'absolute');
        var width = this.element.width();
        if(x > middleX)
            this.element.css("left",x - width);
        else
            this.element.css("left",x);
        if(y > middleY)
            this.element.css("top", y - this.element.height());
        else
            this.element.css("top",  y);
        this.element.draggable("option","containment",[10,10,
            $("body").width() - this.element.width() - 30,
            $("body").height() - this.element.height() - 50]);
    }

    public isShown() : boolean {
        return this.element.is(":visible");
    }

    public getId()
    {
        return this.id;
    }

    protected insertTemplate(template : string, value : string)
    {
        this.template = this.template.replace("{" + template + "}", value);
    }
    protected abstract submit(e : JQueryEventObject);

    public hide() {
        this.element.hide();
    }
}