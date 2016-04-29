/**
 * Created by Jason on 4/27/2016.
 */
export interface Serializable {
    toPlainObject() : {};
    fromPlainObject(obj : {}) : void;
}