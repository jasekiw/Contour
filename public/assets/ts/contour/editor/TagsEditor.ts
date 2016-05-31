import {TagsApi} from "../api/TagsApi";
import {PlainTag} from "../data/datatag/DataTag";
import {PopUpScreen} from "../ui/PopUpScreen";
var template = `
<div class="panel panel-default tagsEditor popup" id="{id}" style="display:none;">
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
            <div class="currentTags">
                <div class="tag_navigation"></div>
                <div class="general_tags">
                </div>
            </div>
            <div class="options">
                <input type="submit" value="Save" class="submit" />
            </div>
        </div>
    </div>
</div>
`;
export class TagsEditor extends PopUpScreen
{
    protected tagsToUse : PlainTag[];
    protected currentTags : PlainTag[];
    protected currentParentId : number;
    protected callback : (tags : PlainTag[]) => void;

    constructor()
    {
        super("TagsEditor", template);
        this.insertElement(undefined,false);
        this.element.find(".exitButton").click(() => {
            this._hide();
        });
        this.element.find('.tag_navigation').on("dblclick",".editor_tag", (e : JQueryEventObject) => {

            let parentId = $(e.target).attr("tag");
            console.log(parentId);
            this.setUpCurrentTags(parseInt(parentId));
        });
        this.element.find(".submit").click((e) => this.save());
        this.element.find(".currentTags").find(".general_tags").on("dblclick", ".editor_tag", (e : JQueryEventObject) => {
            this.addTagToUse($(e.target));
        })

    }

    public show(tagIds : number[], parentId: number, callback? : (tags : PlainTag[]) => void) {
        this.callback = callback;
        this.currentParentId = parentId;
        TagsApi.getByIds(tagIds, (tags) => {
            this.tagsToUse = tags;
            this.populateTags();
            this.setUpCurrentTags(parentId);
            this._show();
        });
    }
    protected addTagToUse(e : JQuery)
    {
        let tag = this.fromHtmlTag(e);
        e.detach();
        this.tagsToUse.push(tag);
        this.element.find('.tagsToUse').append(e);
    }

    protected save()
    {
        this._hide();
        this.callback(this.tagsToUse);
    }

    protected setUpCurrentTags(parentId)
    {
        TagsApi.getById(parentId, (parent) => {
            TagsApi.getChildren(parentId,(tags) => {
                this.currentTags = tags;
                this.populateCurrentTags(parent);

            });
        });

    }

    protected populateTags()
    {
        //
        let $tagsToAdd = $();
        this.tagsToUse.forEach((tag,index) => {
            $tagsToAdd = $tagsToAdd.add( this.makeHtmlTag(tag));
        });
        this.element.find(".tagsToUse").html($tagsToAdd);
    }
    protected populateCurrentTags(parent : PlainTag)
    {
        let newCurrentTags = $();
        let newNaviationTags = $();
        this.currentTags.forEach((tag,index) => {
            if(tag.type != "primary" && tag.type != "folder")
                newCurrentTags = newCurrentTags.add( this.makeHtmlTag(tag));
            else if(tag.type == "folder")
                newNaviationTags = newNaviationTags.add(this.makeHtmlTag(tag))

        });

        let goUpDirElem = this.makeHtmlTag({
            id: parent.parentId,
            name: "..",
            parentId: 0,
            sort_number : 0,
            type: "folder",
            typeId: 0

        });
        this.element.find(".currentTags").find(".general_tags").html(newCurrentTags);

        this.element.find(".currentTags").find(".tag_navigation").html(goUpDirElem.add(newNaviationTags));
    }

    protected makeHtmlTag(tag : PlainTag) : JQuery
    {
        let tagTemplate = `
            <div class="editor_tag" tag="{id}" sort_number="{sort}" type="{type}" name="{name}" typeId="{type_id}" parentId="{parent_id}">
                {name}
            </div>
            `;
        tagTemplate = tagTemplate.replace("{id}", tag.id.toString());
        tagTemplate = tagTemplate.replace("{sort}", tag.sort_number.toString());
        tagTemplate = tagTemplate.replace("{name}", tag.name);
        tagTemplate = tagTemplate.replace("{name}", tag.name);
        tagTemplate = tagTemplate.replace("{type}", tag.type);
        tagTemplate = tagTemplate.replace("{type_id}", tag.typeId.toString());
        tagTemplate = tagTemplate.replace("{parent_id}", tag.parentId.toString());
        return $(tagTemplate);
    }
    protected fromHtmlTag(htmlTag : JQuery) : PlainTag
    {
        return  {
            id: parseInt(htmlTag.attr("tag")),
            sort_number: parseInt(htmlTag.attr("sort_number")),
            type: htmlTag.attr("type"),
            name: htmlTag.attr("name"),
            typeId: parseInt(htmlTag.attr("typeId")),
            parentId: parseInt(htmlTag.attr("parentId"))
        }
    }






}