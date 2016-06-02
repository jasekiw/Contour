import {Cloneable} from "./Cloneable";
export class IntMap<V> implements Cloneable<IntMap<V>>
{

    private data : {[name : string] : V };

    constructor()
    {
        this.data = {};
    }

    /**
     *
     * @param key
     * @param value
     */
    public set(key : number, value : V)
    {
        if (typeof key != "number")
            console.log(key + " is not a number");
        this.data[key] = value;
    }

    /**
     *
     * @param key
     */
    public remove(key : number)
    {
        delete this.data[key];
    }

    /**
     *
     * @param key
     * @returns The item to get
     */
    public get(key : number) : V
    {
        return this.data[key];
    }

    /**
     *
     * @param callback
     */
    public iterate(callback : (key : number, val : V) => any)
    {
        for (var akey in this.data) {
            let shallContinue = callback(parseInt(akey), this.data[akey]);
            if (shallContinue == undefined)
                continue;
            if (shallContinue === false)
                break;
        }
    }

    /**
     * @returns {{}}
     */
    public getObject()
    {
        return this.data;
    }

    /**
     *
     * @returns {number}
     */
    public length()
    {
        return Object.keys(this.data).length;
    }

    /**
     *
     * @returns {string[]}
     */
    public keys() : string[]
    {
        return Object.keys(this.data);
    }

    /**
     *
     * @returns {Array}
     */
    public intKeys() : number[]
    {
        let keys = [];
        Object.keys(this.data).forEach((key) =>
        {
            keys.push(parseInt(key));
        });
        return keys;
    }

    /**
     *
     * @returns A cloned copy of the IntMap
     */
    public clone() : IntMap<V>
    {
        let intmap = new IntMap<V>();
        let newData : {[name : string] : V } = {};
        Object.keys(this.data).forEach((key) => newData[key] = this.data[key]);
        intmap.data = newData;
        return intmap;
    }

    /**
     * finds the values by keys that this map has and the other does not and also what the other map has and this does not.
     * @param othermap
     * @param callback
     */

    public diff(othermap : IntMap<V>, callback : (mine : IntMap<V>, theirs : IntMap<V>) => void)
    {
        let mine = new IntMap<V>();
        let theirs = new IntMap<V>();
        this.iterate((i,v) => {
            if(othermap.get(i) == undefined)
                mine.set(i, v);
        });
        othermap.iterate((i,v) => {
            if(this.get(i) == undefined)
                theirs.set(i, v);
        });
        callback(mine, theirs);
    }
}