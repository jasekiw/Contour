import {UIElement} from "./UIElement";
export abstract class HideableInterface extends UIElement
{
   protected _show()
   {
       this.element.css("opacity", "0");
       this.element.show();
       this.element.animate({
           opacity: 1
       }, 300)
   }
    protected _hide()
    {
        this.element.animate({
            opacity: 0
        }, 300, () =>
        {
            this.element.hide();
        });
    }

}