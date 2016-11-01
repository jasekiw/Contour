/**
 * Created by Jason Gallavin on 5/25/2016.
 */
var template = `
<div class="dialogbox" id="{id}">
    <h3 class="title">{title}</h3>
    <form class="form-inline" method="POST" action="{action}">
        {content}
        <button class="btn btn-danger cancel">Cancel</button>
        <input type="submit" value="{submitText}" class="btn btn-primary submit" />
    </form>
</div>
`;
var style = `
<style type="text/css">
    .dialogbox {
        border-radius: 5px;
        box-shadow: 0 0 44px rgba(0,0,0,0.4), 0 0 4px rgba(0,0,0,0.7), 0 0 44px #9DD0C6;
        padding:20px;
        display:none;
        background-color:#F1F1F1;
    }
    .dialogbox .title {
        margin-top:0;
    }
    </style>
`;

import {UIElement} from "./../UIElement";

export abstract class GeneralDialogBox extends UIElement
{

    protected form : JQuery;
    protected onSubmit : (response : {}) => void;
    protected $body : JQuery;
    private visible = false;

    /**
     *
     * @param title
     * @param submitText
     * @param content
     */
    constructor(title : string, submitText : string, content : string)
    {
        super("dialog", style + template);
        this.$body = $("body");
        this.insertTemplate("title", title);
        this.insertTemplate("submitText", submitText);
        this.insertTemplate("content", content);

        this.insertElement();
        this.element.draggable({
            containment: [10, 10,
                this.$body.width() - this.element.width() - 30,
                this.$body.height() - this.element.height() - 50]
        });
        this.form = this.element.find("form");
        this.form.submit(e => this.submit(e));
        this.form.find('[name="name"]')
        this.form.find('[type="submit"]').click((e) => this.submit(e));
        this.element.find('form .cancel').click((e) =>
        {
            e.preventDefault();
            this.hide();
        });
        this.element.click((e : JQueryEventObject) =>
        {
            e.stopPropagation();
        });
        this.$body.click((e : JQueryEventObject) =>
        {
            if (this.isShown() && !this.visible)
                this.visible = true;
            else {
                this.visible = false;
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
    protected _show(onSubmit : (response : {}) => void, x : number, y : number)
    {
        this.onSubmit = onSubmit;
        var middleX = $(window).width() / 2;
        var middleY = $(window).height() / 2;
        this.element.css("right", '');
        this.element.css("left", '');
        this.element.css("top", '');
        this.element.css("bottom", '');
        this.element.css('position', 'absolute');
        var width = this.element.width();
        if (x > middleX)
            this.element.css("left", x - width);
        else
            this.element.css("left", x);
        if (y > middleY)
            this.element.css("top", y - this.element.height());
        else
            this.element.css("top", y);
        this.element.draggable("option", "containment", [10, 10,
            this.$body.width() - this.element.width() - 30,
            this.$body.height() - this.element.height() - 50]);
    }

    public isShown() : boolean
    {
        return this.element.is(":visible");
    }

    public getId()
    {
        return this.id;
    }

    protected abstract submit(e : JQueryEventObject);

    public hide()
    {
        this.visible = false;
        this.element.hide();
    }
}