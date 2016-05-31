import {HideableInterface} from "./HideableInterface";
import {BackgroundFilter} from "./BackgroundFilter";
export class PopUpScreen extends HideableInterface
{

    private filter : BackgroundFilter;
    constructor(name : string, template : string)
    {
        super(name,template);
        this.filter = new BackgroundFilter();
    }
    protected _show()
    {
        $("body").css("max-height", "100%").css("overflow-y", "hidden");
        this.filter.show();
        this.element.css("position", "fixed");
        this.element.css("left", 10 + "px");
        this.element.css("top", 10 + "px");
        this.element.css("bottom", 10 + "px");
        this.element.css("right", 10 + "px").css("z-index", "9001");
        super._show();
    }
    protected _hide()
    {
        this.filter.hide();
        super._hide();
        $("body").css("max-height", "").css("overflow-y", "");
    }
}