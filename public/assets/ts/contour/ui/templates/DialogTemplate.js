define(["require", "exports"], function (require, exports) {
    "use strict";
    /**
     * Created by Jason Gallavin on 4/22/2016.
     */
    exports.template = "\n<div class=\"dialogbox\" id=\"{id}\">\n    <h3 class=\"title\">{title}</h3>\n    <form class=\"form-inline\" method=\"POST\" action=\"{action}\">\n        {content}\n        <button class=\"btn btn-danger cancel\">Cancel</button>\n        <input type=\"submit\" value=\"{submitText}\" class=\"btn btn-primary submit\" />\n    </form>\n</div>\n";
    exports.style = "\n<style type=\"text/css\">\n    .dialogbox {\n        border-radius: 5px;\n        box-shadow: 0 0 44px rgba(0,0,0,0.4), 0 0 4px rgba(0,0,0,0.7), 0 0 44px #9DD0C6;\n        padding:20px;\n        display:none;\n        background-color:#F1F1F1;\n    }\n    .dialogbox .title {\n        margin-top:0;\n    }\n\n    </style>\n";
});
//# sourceMappingURL=DialogTemplate.js.map