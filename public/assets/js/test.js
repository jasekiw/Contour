/**
 * Created by Jason Gallavin on 10/23/2015.
 */
/// <reference path="typings/jquery/jquery.d.ts" />
var Test = (function () {
    function Test() {
        var style = "<style type=\"text/css\">\n            .tag_editor {\n                border-radius: 5px;\n                box-shadow: 0 0 5px rgba(0,0,0,0.7);\n                display:inline-block;\n                padding:20px;\n                display:none;\n                background-color:#F1F1F1;\n            }\n            .tag_editor .title {\n                margin-top:0;\n            }\n                /**\n                 ***Rename UI\n                 **/\n            .tag_editor.rename_ui input[type=submit] {\n                margin: 5px 0;\n                float:right;\n            }\n                /**\n                 ***Delete UI\n                 **/\n            .tag_editor.delete_ui .decision {\n                text-align:center;\n            }\n            .tag_editor.delete_ui input[type=submit] {\n                margin: 5px 10px;\n            }\n        </style>";
        var renameUI = "<div class=\"tag_editor rename_ui\">\n            <h3 class=\"title\">Rename</h3>\n            <form method=\"POST\" action=\"ajax/tageditor/rename\">\n                <input type=\"text\" value=\"name\" class=\"form-control name\" />\n                <input type=\"checkbox\" name=\"recursive\" value=\"true\">Also rename other tags with the same name.\n                <input type=\"submit\" value=\"Cancel\" class=\"btn btn-danger cancel\" />\n                <input type=\"submit\" value=\"Save\" class=\"btn btn-primary submit\" />\n            </form>\n        </div>\n        ";
    }
    return Test;
})();
var mytest = new Test();
//# sourceMappingURL=test.js.map