import {UIElement} from "../ui/UIElement";
import {TagsApi} from "../api/TagsApi";
import {PlainTag} from "../data/datatag/DataTag";
var template = `
<div class="panel panel-default tagsEditor" id="{id}" style="display:none;">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Tags</h3>
        <a class="exitButton" href="javascript:void(0);" ><i class="fa fa-times"></i></a>
    </div>
    <div class="panel-body">
        <div class="top_section">
            <div class="tagsToUse">
            
            </div>
        </div>
        <div class="bottom_section">
            <div class="tagsAvailable">
            
            </div>
            <div class="options">
                <input type="submit" value="Save" class="submit" />
            </div>
        </div>
    </div>
</div>
`;
export class TagsEditor extends UIElement
{
    protected tagsToUse : PlainTag[];
    protected currentTags : PlainTag[];
    protected currentParentId : number;
    protected callback : (tags : PlainTag[]) => void;
    constructor()
    {
        super("TagsEditor", template);
        this.insertElement(undefined,false);

    }

    public show(tagIds : number[], parentId: number, callback? : (tags : PlainTag[]) => void) {
        this.callback = callback;
        this.currentParentId = parentId;
        TagsApi.getByIds(tagIds, (tags) => {
            this.tagsToUse = tags;

            TagsApi.getChildren(parentId,(tags) => {
                this.currentTags = tags;
                this.populateTags();
                this.element.show();
            })
        });
    }

    protected populateTags()
    {
        //
        let $tagsToAdd = $();
        let newCurrentTags = $();
        this.tagsToUse.forEach((tag,index) => {
            $tagsToAdd = $tagsToAdd.add( this.makeHtmlTag(tag));
        });
        this.currentTags.forEach((tag,index) => {
            newCurrentTags = newCurrentTags.add( this.makeHtmlTag(tag));
        });
        this.element.find(".currentTags").html(newCurrentTags);
        this.element.find(".tagsToUse").html($tagsToAdd);
    }

    protected makeHtmlTag(tag : PlainTag) : JQuery
    {
        let tagTemplate = `
            <div class="tag" tag="{id}" sort_number="{sort}">
                <span class="name">{name}</span>
                <span class="type">{type}</span>
            </div>
            `;
        tagTemplate = tagTemplate.replace("{id}", tag.id.toString());
        tagTemplate = tagTemplate.replace("{sort}", tag.sort_number.toString());
        tagTemplate = tagTemplate.replace("{name}", tag.name);
        tagTemplate = tagTemplate.replace("{type}", tag.type);
        return $(tagTemplate);
    }






}