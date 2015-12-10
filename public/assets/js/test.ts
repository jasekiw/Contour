/**
 * Created by Jason Gallavin on 10/23/2015.
 */

    /// <reference path="typings/jquery/jquery.d.ts" />

class Test {
    private myvar : String;

    constructor()
    {

        var style =
        `<style type="text/css">
            .tag_editor {
                border-radius: 5px;
                box-shadow: 0 0 5px rgba(0,0,0,0.7);
                display:inline-block;
                padding:20px;
                display:none;
                background-color:#F1F1F1;
            }
            .tag_editor .title {
                margin-top:0;
            }
                /**
                 ***Rename UI
                 **/
            .tag_editor.rename_ui input[type=submit] {
                margin: 5px 0;
                float:right;
            }
                /**
                 ***Delete UI
                 **/
            .tag_editor.delete_ui .decision {
                text-align:center;
            }
            .tag_editor.delete_ui input[type=submit] {
                margin: 5px 10px;
            }
        </style>`;
        var renameUI =
        `<div class="tag_editor rename_ui">
            <h3 class="title">Rename</h3>
            <form method="POST" action="ajax/tageditor/rename">
                <input type="text" value="name" class="form-control name" />
                <input type="checkbox" name="recursive" value="true">Also rename other tags with the same name.
                <input type="submit" value="Cancel" class="btn btn-danger cancel" />
                <input type="submit" value="Save" class="btn btn-primary submit" />
            </form>
        </div>
        `;
    }
}

var mytest = new Test();
mystest.