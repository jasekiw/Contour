/**
 * Created by Jason on 5/31/2016.
 */
class Map<K extends number | string, V>
{
    private data : {[name : string] : V };

    constructor()
    {
        this.data = {};
    }
    set(key: K, value: V): void;
    public set(key : any, value : V)
    {
        this.data[key] = value;
    }
    remove(a: K): void;
    public remove(key : any)
    {
        delete this.data[key];
    }
    get(key: K): void;
    public get(key : any)
    {
        return this.data[key];
    }
    public iterate(callback : (key : string , val : V) => boolean | void)
    {
        for(var akey in this.data)
        {
            let shallContinue = callback(akey, this.data[akey]);
            if(shallContinue == undefined)
                continue;
            if(shallContinue == false)
                break;
        }
    }
    public getObject()
    {
        return this.data;
    }
    public length()
    {
        return Object.keys(this.data).length;
    }
    public keys() : string[]
    {
        return Object.keys(this.data);
    }
}