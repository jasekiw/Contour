/**
 * Created by Jason Gallavin on 12/22/2015.
 */
    ///<reference path="references.d.ts" />
class DataTagInterfacer {

    public static getChildren(id : number, functiontoCall : (e) => void) : void
    {
        (new Ajax).get('/ajaxtags/get_children/' + id, functiontoCall);
    }

    public static getChildrenRecursive(id : number, functiontoCall : (e) => void) : void
    {
        (new Ajax).get('/ajaxtags/get_children_recursive/' + id, functiontoCall);
    }

}