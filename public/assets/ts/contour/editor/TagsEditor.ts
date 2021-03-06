import {TagsApi} from "../api/TagsApi";
import {PlainTag} from "../data/datatag/DataTag";
import {PopUpScreen} from "../ui/PopUpScreen";
import {IntMap} from "../lib/IntMap";
var template = `
<div class="panel panel-default tagsEditor popup" id="{id}" style="display:none;">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Tags</h3>
        <a class="exitButton" href="javascript:void(0);" ><i class="fa fa-times"></i></a>
    </div>
    <div class="panel-body">
        <div class="top_section">
            <h3>Referenced tags</h3>
            <div class="tagsToUse">
            
            </div>
        </div>
        <div class="bottom_section">
            <div class="currentTags">
                <h3>Navigation</h3>
                <div class="tag_navigation"></div>
                <h3>Available Tags</h3>
                <div class="general_tags">
                </div>
                <div class="newTagOptions">
                    <input type="text" class="form-inline newTagName" />
                    <button class="evergreen_button">Add Tag</button>
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
    protected tagsToUse : IntMap<PlainTag>;
    protected tagsStarted : IntMap<PlainTag>;
    protected currentTags : PlainTag[];
    protected currentParentId : number;
    protected callback : (addedTags : IntMap<PlainTag>, deletedTags) => void;
    protected exitCallBack : () => void;
    
    constructor()
    {
        super("TagsEditor", template);
        this.tagsToUse = new IntMap<PlainTag>();
        this.insertElement( undefined , false);
        this.element.find(".exitButton").click(() => {

            this._hide();
            if(this.exitCallBack != undefined)
                this.exitCallBack();
        });
        this.element.find('.tag_navigation').on("dblclick",".editor_tag", (e : JQueryEventObject) => {

            let parentId = $(e.target).attr("tag");
            console.log(parentId);
            this.setUpCurrentTags(parseInt(parentId));
        });
        this.element.find(".submit").click((e) => this.save());
        this.element.find(".currentTags").find(".general_tags").on("dblclick", ".editor_tag", (e : JQueryEventObject) => {
            this.addTagToUse($(e.target));
        });
        this.element.find(".tagsToUse").on("dblclick", ".editor_tag", (e : JQueryEventObject) => {
            this.removeTagFromReferenced($(e.target));
        });
        this.element.find(".newTagOptions button").click(() => this.createTag());

    }

    /**
     * 
     * @param tagIds
     * @param parentId
     * @param callback
     * @param exitCallBack
     */
    public show(tagIds : number[], parentId: number, callback? : (addedTags : IntMap<PlainTag>, deletedTags : IntMap<PlainTag> ) => void, exitCallBack? : () => void) {
        this.callback = callback;
        this.exitCallBack = exitCallBack;
        this.currentParentId = parentId;
        if(tagIds.length == 0)
        {
            this.tagsToUse = new IntMap<PlainTag>();
            this.tagsStarted = this.tagsToUse.clone();
            this.populateTags();
            this.setUpCurrentTags(parentId);
            this._show();
        }
        else
            TagsApi.getByIds(tagIds, (tags) => {

                this.tagsToUse = new IntMap<PlainTag>();
                if(tags != undefined)
                    tags.forEach((tag, i) => {
                        this.tagsToUse.set(tag.id,tag);
                    });
                this.tagsStarted = this.tagsToUse.clone();
                this.populateTags();
                this.setUpCurrentTags(parentId);
                this._show();
            });
    }
    protected addTagToUse(e : JQuery)
    {
        let tag = this.fromHtmlTag(e);
        e.detach();
        this.tagsToUse.set(tag.id, tag);
        this.element.find('.tagsToUse').append(e);
    }
    protected createTag()
    {
        let name = this.element.find(".newTagOptions .newTagName").val();
        this.element.find(".newTagOptions .newTagName").val("");
        if(name.length > 0)
            TagsApi.create(name, this.currentParentId, 0, "general", (tag)=> {
                this.addTagToCurrent(tag);

            });
        else
            alert("please enter a name before adding the tag")
    }
    protected removeTagFromReferenced(e : JQuery)
    {
        let tagToRemove = this.fromHtmlTag(e);
        e.detach();
        this.tagsToUse.remove(tagToRemove.id);
        this.refreshCurrentTags();
    }

    protected save()
    {
        this._hide();
        let deletedTags = new IntMap<PlainTag>();
        this.tagsStarted.iterate((index, tag) => {
            let getter = this.tagsToUse.get(index);
            if(this.tagsToUse.get(index) == undefined)
                deletedTags.set(index, tag);
        });
        let addedTags = new IntMap<PlainTag>();
        this.tagsToUse.iterate((index, tag) => {
            if(this.tagsStarted.get(index) == undefined)
                addedTags.set(index, tag);
        });
        this.callback(addedTags, deletedTags);
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
        this.tagsToUse.iterate((index, tag) => {
            $tagsToAdd = $tagsToAdd.add( this.makeHtmlTag(tag));
        });

        this.element.find(".tagsToUse").html($tagsToAdd);
    }
    protected refreshCurrentTags()
    {
        let parentId = parseInt(this.element.find(".currentTags").find(".general_tags").attr("parent"));
        this.setUpCurrentTags(parentId);
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
        this.element.find(".currentTags").find(".general_tags").attr("parent", parent.id);
        this.element.find(".currentTags").find(".tag_navigation").html(goUpDirElem.add(newNaviationTags));
    }
    protected addTagToCurrent(tag : PlainTag)
    {
        this.element.find(".currentTags").find(".general_tags").append(this.makeHtmlTag(tag));
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