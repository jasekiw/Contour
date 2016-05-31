/**
 * Created by Jason on 5/30/2016.
 */



export class BackgroundFilter
{
    private element: JQuery;
    constructor()
    {
        this.element = $('<div class="background_filter"></div>');
        this.element.css("background", "black")
            .css("display", "none")
            .css("top", 0)
            .css("bottom", "0")
            .css("left", "0")
            .css("right", "0")
            .css("position", "fixed")
            .css("opacity", "0")
            .css("z-index", "8000");
        $("body").append(this.element);
    }

    public show()
    {
        this.element.show();
        this.element.animate(
            {
                opacity: 0.4
            }, 500
        );
    }
    public hide()
    {
        this.element.animate(
            {
                opacity: 0
            }, 300, () =>
            {
                this.element.hide();
            }
        );

    }


}