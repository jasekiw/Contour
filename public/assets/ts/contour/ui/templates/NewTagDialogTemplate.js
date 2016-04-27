/**
 * Created by Jason Gallavin on 4/21/2016.
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    exports.template = "\n<div class=\"tag_editor create_ui\" id=\"[id]\">\n    <h3 class=\"title\">Create</h3>\n    <form class=\"form-inline\" method=\"POST\" action=\"/tags/create\">\n        <input type=\"hidden\" name=\"parent_id\" value=\"\" />\n        <div class=\"form-group\">\n            <label>Name</label>\n            <input class=\"form-control\" type=\"text\" name=\"name\" value=\"\" />\n        </div>\n        <div class=\"form-group\">\n            <select class=\"form-control\" name=\"type\"></select>\n        </div>\n        \n        <input type=\"submit\" value=\"Cancel\" class=\"btn btn-danger cancel\" />\n        <input type=\"submit\" value=\"Create\" class=\"btn btn-primary submit\" />\n    </form>\n</div>\n";
    exports.style = "\n    <style type=\"text/css\">\n    .tag_editor {\n        border-radius: 5px;\n        box-shadow: 0 0 5px rgba(0,0,0,0.7);\n        padding:20px;\n        display:none;\n        background-color:#F1F1F1;\n    }\n    .tag_editor .title {\n        margin-top:0;\n    }\n    /**\n     ***Rename UI\n     **/\n    .tag_editor.rename_ui input[type=submit] {\n        margin: 5px 0;\n        float:right;\n    }\n    /**\n     ***Delete UI\n     **/\n    .tag_editor.delete_ui .decision {\n        text-align:center;\n    }\n    .tag_editor.delete_ui input[type=submit] {\n        margin: 5px 10px;\n    }\n    </style>\n";
});
//# sourceMappingURL=NewTagDialogTemplate.js.map