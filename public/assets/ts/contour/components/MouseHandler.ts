/**
 * Created by Jason Gallavin on 4/22/2016.
 */
export class MouseHandler
{

    public x : number;
    public y : number;

    constructor()
    {
        this.x = 0;
        this.y = 0;
        $(document).mousemove((e) =>
        {
            this.x = e.pageX;
            this.y = e.pageY;
        })
    }

}

export var mouse = new MouseHandler();