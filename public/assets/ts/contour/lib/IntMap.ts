
export class IntMap<V>
{
    private data : {[name : string] : V };
    constructor()
    {
        this.data = {};
    }

    public set(key : number, value : V)
    {
        if(typeof key != "number")
            console.log(key + " is not a number");
        this.data[key] = value;
    }

    public remove(key : number)
    {
        delete this.data[key];
    }

    public get(key : number)
    {
        return this.data[key];
    }
    public iterate(callback : (key : number , val : V) => boolean | void)
    {
        for(var akey in this.data)
        {
            let shallContinue = callback(parseInt(akey), this.data[akey]);
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