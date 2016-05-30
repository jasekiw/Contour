/**
 * UI element. assigns an id and template automatically
 */
export abstract class UIElement
{
    protected template : string;
    protected id : string;
    protected element : JQuery;

    constructor(name : string, template : string)
    {
        this.template = template;
        this.id = name + "_" + (new Date().getTime().toString());
        this.insertTemplate("id", this.id);
    }

    protected insertTemplate(template : string, value : string)
    {
        this.template = this.template.replace("{" + template + "}", value);
    }

    /**
     * Inserts the element into the dom
     * @param location the selector to append the object
     * @param show determines whether to hide or show the element when it is added to the dom
     */
    protected insertElement(location? : string, show = true )
    {
        let newElem = $(this.template);
        if(!show)
            newElem.hide();
        if (location == undefined)
            $("body").append(newElem);
        else
            $(location).append(newElem);
        this.element = $("#" + this.id);


    }
}