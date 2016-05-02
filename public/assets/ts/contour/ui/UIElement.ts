/**
 * UI element. assigns an id and template automatically
 */
export abstract class UIElement {
    protected template : string;
    protected id : string;
    protected element : JQuery;
    constructor(name: string, template : string) {
        this.template = template;
        this.id =  name + "_" + (new Date().getTime().toString());
        this.insertTemplate("id", this.id );
    }
    protected insertTemplate(template : string, value : string)
    {
        this.template = this.template.replace("{" + template + "}", value);
    }

    /**
     * Inserts the element into the dom
     * @param location the selector to append the object
     */
    protected insertElement(location? : string){
        if(location == undefined)
            $("body").append(this.template);
        else
            $(location).append(this.template);
        this.element = $("#" + this.id);
    }
}