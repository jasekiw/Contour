define(["require", "exports", "../Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 4/22/2016.
     */
    var TagsApi = (function () {
        function TagsApi() {
        }
        /**
         *
         * @param  name the name of the tag
         * @param  parentId the parent id that the tag will reside under
         * @param  type the name of the type to use
         * @param  funtionToCall The function to call to give the tag object to
         */
        TagsApi.create = function (name, parentId, type, funtionToCall) {
            var data = {
                name: name,
                parent_id: parentId,
                type: type
            };
            new Ajax_1.Ajax().post("/api/tags/create", data, function (e) {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);
            });
        };
        /**
         *
         * @param id
         * @param newName
         * @param funtionToCall
         */
        TagsApi.rename = function (id, newName, funtionToCall) {
            var data = {
                id: id,
                newName: newName
            };
            new Ajax_1.Ajax().post("/api/tags/rename", data, function (e) {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e.payload);
            });
        };
        /**
         *
         * @param id
         * @param funtionToCall
         */
        TagsApi.deleteTag = function (id, funtionToCall) {
            var data = {
                id: id
            };
            new Ajax_1.Ajax().post("/api/tags/delete", data, function (e) {
                if (e.success)
                    if (funtionToCall !== undefined)
                        funtionToCall(e);
            });
        };
        return TagsApi;
    }());
    exports.TagsApi = TagsApi;
});
//# sourceMappingURL=Tags.js.map