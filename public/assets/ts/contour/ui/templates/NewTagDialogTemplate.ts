/**
 * Created by Jason Gallavin on 4/21/2016.
 */

export var template = `
<div class="tag_editor create_ui" id="[id]">
    <h3 class="title">Create</h3>
    <form class="form-inline" method="POST" action="/tags/create">
        <input type="hidden" name="parent_id" value="" />
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="" />
        </div>
        <div class="form-group">
            <select class="form-control" name="type"></select>
        </div>
        
        <button class="btn btn-danger cancel">Cancel</button>
        <input type="submit" value="Create" class="btn btn-primary submit" />
    </form>
</div>
`;

export var content = `
        <input type="hidden" name="parent_id" value="" />
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="" />
        </div>
        <div class="form-group">
            <select class="form-control" name="type"></select>
        </div>
`;

export var style = `
    <style type="text/css">
    .tag_editor {
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0,0,0,0.7);
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
    </style>
`;