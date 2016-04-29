/**
 * Created by Jason Gallavin on 4/22/2016.
 */
export var template = `
<div class="dialogbox" id="{id}">
    <h3 class="title">{title}</h3>
    <form class="form-inline" method="POST" action="{action}">
        {content}
        <button class="btn btn-danger cancel">Cancel</button>
        <input type="submit" value="{submitText}" class="btn btn-primary submit" />
    </form>
</div>
`;
export var style = `
<style type="text/css">
    .dialogbox {
        border-radius: 5px;
        box-shadow: 0 0 44px rgba(0,0,0,0.4), 0 0 4px rgba(0,0,0,0.7), 0 0 44px #9DD0C6;
        padding:20px;
        display:none;
        background-color:#F1F1F1;
    }
    .dialogbox .title {
        margin-top:0;
    }

    </style>
`;
