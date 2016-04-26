import DraggableEvent = JQueryUI.DraggableEvent;
import DraggableOptions = JQueryUI.DraggableOptions;
import {Ajax} from "../Ajax";
import {template as linkTemplate} from "./template/LinkTemplate";
/**
 * Created by Jason Gallavin on 1/26/2016.
 */
export class MenuEditor {
    private editor : JQuery;
    private linksContainer : JQuery;
    private addNewButton : JQuery;
    private menuID : number;
    public static LINKHTML = linkTemplate;
    constructor(editor : JQuery) {
       

        this.editor = editor;
        this.menuID = parseInt(this.editor.attr("menu"));
        this.linksContainer = this.editor.find(".links");
        this.addNewButton = this.editor.find(".new i");
        this.addNewButton.on("click",(e :JQueryEventObject)  => this.addNewLink(e));
        this.linksContainer.find(".menuLink").each( (index : number, element : Element) => {
            var $element = $(element);
            $element.css("order", $element.attr("order"));
        });
        this.linksContainer.find(".delete i").on("click", (e : JQueryEventObject) => this.handleDelete(e));
        this.makeDraggable(this.linksContainer.find(".menuLink"));
        this.editor.find("input[type='submit']").on("click", (e :JQueryEventObject) => this.save(e));

    }
    public dragStop(e : Event, options: DraggableOptions ) : void {
        var helper = $(options.helper);
        var draggerTop : number = helper.offset().top + (helper.height() / 2);
        var links = this.linksContainer.find(".menuLink");
        var linkLength = links.length;
        links.each( (index : number, element : Element) => {
            var middle = $(element).offset().top + ($(element).height() / 2);
            var islastElement = (linkLength - index) == 1;
            var lastElement = $(links.get(index - 1));
            var lastMiddle = index == 0 ? lastElement.offset().top + (lastElement.height() / 2) : 0;
            var nextElement = $(links.get(index + 1));
            var nextMiddle = !islastElement ? nextElement.offset().top + (nextElement.height() / 2) : 0;


            if (draggerTop < middle && index == 0)
            {
                helper.detach();
                $(element).before(helper);
                return false; // break;
            }
            else if(draggerTop >  middle && islastElement)
            {
                helper.detach();
                $(element).after(helper);
                return false; // break;
            }
            else if(index > 0  &&  draggerTop > lastMiddle && draggerTop < middle)
            {
                helper.detach();
                $(element).before(helper);
                return false; // break;
            }
            else if(!islastElement &&  draggerTop > middle && draggerTop < nextMiddle)
            {
                helper.detach();
                $(element).after(helper);
                return false; // break;
            }
            else
            {
                console.log("a weird case was hit");
                return true; // continue
            }
        });
        this.normalizeSorts();

        helper.css("top", "0");
        helper.css("z-index", "50");

    }

    public addNewLink(e : JQueryEventObject)
    {
        var newElement = $(MenuEditor.LINKHTML);
        newElement.attr("order", this.getHighestSort() + 1);
        this.linksContainer.append(newElement);
        this.makeDraggable(newElement);
        this.normalizeSorts();
        newElement.find(".delete i").on("click", (e : JQueryEventObject) => this.handleDelete(e));
    }
    public getHighestSort() : number
    {
        return parseInt( this.linksContainer.find(".menuLink").last().attr("order"));
    }
    public handleDelete(e: JQueryEventObject )
    {
        $(e.target).parents(".menuLink").fadeOut(200,() => {
            $(e.target).parents(".menuLink").remove();
            this.normalizeSorts();
        } );


    }
    public makeDraggable(elements : JQuery)
    {
        elements.draggable( {
            distance : 15,
            axis: 'y',
            handle: '.grabber i',
            start: (e : Event) => $(e.target).css("z-index", "100"),
            stop: (e : Event, options: DraggableOptions ) => this.dragStop(e, options)
        });
    }
    private normalizeSorts()
    {
        var links = this.linksContainer.find(".menuLink");
        links.each((index : number, element : Element) => {
            $(element).attr("order", index + 1);
            $(element).css("order", index + 1);
        });
    }
    public save(e : JQueryEventObject)
    {
        e.preventDefault();
        var linksToSave : {name : string, order: number, link: string, icon : string}[] = [] ;
        this.linksContainer.find(".menuLink").each((index: number, element : Element) => {
            var order : number = parseInt(element.getAttribute("order"));
            var $element = $(element);
            var name = $element.find("input[name='name']").val();
            var link = $element.find("input[name='href']").val();
            linksToSave.push({link : link,name: name, order: order, icon: "" })
        });
       console.log("saving");

        (new Ajax()).put("/menu/" + this.menuID, {links: linksToSave, isAjax: true}, (e) => {
            if(e.success)
                window.location.href = e.redirect;
        });
        return false;
    }


}