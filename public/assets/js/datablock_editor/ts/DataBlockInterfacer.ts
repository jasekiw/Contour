/**
 * Created by Jason Gallavin on 12/22/2015.
 */
///<reference path="references.d.ts" />
class DataBlockInterfacer
{
    public static getById(id: number, functiontoCall : ()=> void ) : void
    {
        (new Ajax).get('/ajaxdatablocks/' + id, functiontoCall);
    }
    public static getMultipleByTags(tags :string[], functiontoCall : () => void ) :void
    {
        var object = {tags: tags};
        (new Ajax).post('/ajaxdatablocks/get_multiple_by_tags', object, functiontoCall);
    }


}