define(["require", "exports", "../Ajax", "./template/LinkTemplate"], function (require, exports, Ajax_1, LinkTemplate_1) {
    "use strict";
    /**
     * Created by Jason Gallavin on 1/26/2016.
     */
    var MenuEditor = (function () {
        function MenuEditor(editor) {
            var _this = this;
            this.editor = editor;
            this.menuID = parseInt(this.editor.attr("menu"));
            this.linksContainer = this.editor.find(".links");
            this.addNewButton = this.editor.find(".new i");
            this.addNewButton.on("click", function (e) { return _this.addNewLink(e); });
            this.linksContainer.find(".menuLink").each(function (index, element) {
                var $element = $(element);
                $element.css("order", $element.attr("order"));
            });
            this.linksContainer.find(".delete i").on("click", function (e) { return _this.handleDelete(e); });
            this.makeDraggable(this.linksContainer.find(".menuLink"));
            this.editor.find("input[type='submit']").on("click", function (e) { return _this.save(e); });
        }
        MenuEditor.prototype.dragStop = function (e, options) {
            var helper = $(options.helper);
            var draggerTop = helper.offset().top + (helper.height() / 2);
            var links = this.linksContainer.find(".menuLink");
            var linkLength = links.length;
            links.each(function (index, element) {
                var middle = $(element).offset().top + ($(element).height() / 2);
                var islastElement = (linkLength - index) == 1;
                var lastElement = $(links.get(index - 1));
                var lastMiddle = index == 0 ? lastElement.offset().top + (lastElement.height() / 2) : 0;
                var nextElement = $(links.get(index + 1));
                var nextMiddle = !islastElement ? nextElement.offset().top + (nextElement.height() / 2) : 0;
                if (draggerTop < middle && index == 0) {
                    helper.detach();
                    $(element).before(helper);
                    return false; // break;
                }
                else if (draggerTop > middle && islastElement) {
                    helper.detach();
                    $(element).after(helper);
                    return false; // break;
                }
                else if (index > 0 && draggerTop > lastMiddle && draggerTop < middle) {
                    helper.detach();
                    $(element).before(helper);
                    return false; // break;
                }
                else if (!islastElement && draggerTop > middle && draggerTop < nextMiddle) {
                    helper.detach();
                    $(element).after(helper);
                    return false; // break;
                }
                else {
                    console.log("a weird case was hit");
                    return true; // continue
                }
            });
            this.normalizeSorts();
            helper.css("top", "0");
            helper.css("z-index", "50");
        };
        MenuEditor.prototype.addNewLink = function (e) {
            var _this = this;
            var newElement = $(MenuEditor.LINKHTML);
            newElement.attr("order", this.getHighestSort() + 1);
            this.linksContainer.append(newElement);
            this.makeDraggable(newElement);
            this.normalizeSorts();
            newElement.find(".delete i").on("click", function (e) { return _this.handleDelete(e); });
        };
        MenuEditor.prototype.getHighestSort = function () {
            return parseInt(this.linksContainer.find(".menuLink").last().attr("order"));
        };
        MenuEditor.prototype.handleDelete = function (e) {
            var _this = this;
            $(e.target).parents(".menuLink").fadeOut(200, function () {
                $(e.target).parents(".menuLink").remove();
                _this.normalizeSorts();
            });
        };
        MenuEditor.prototype.makeDraggable = function (elements) {
            var _this = this;
            elements.draggable({
                distance: 15,
                axis: 'y',
                handle: '.grabber i',
                start: function (e) { return $(e.target).css("z-index", "100"); },
                stop: function (e, options) { return _this.dragStop(e, options); }
            });
        };
        MenuEditor.prototype.normalizeSorts = function () {
            var links = this.linksContainer.find(".menuLink");
            links.each(function (index, element) {
                $(element).attr("order", index + 1);
                $(element).css("order", index + 1);
            });
        };
        MenuEditor.prototype.save = function (e) {
            e.preventDefault();
            var linksToSave = [];
            this.linksContainer.find(".menuLink").each(function (index, element) {
                var order = parseInt(element.getAttribute("order"));
                var $element = $(element);
                var name = $element.find("input[name='name']").val();
                var link = $element.find("input[name='href']").val();
                linksToSave.push({ link: link, name: name, order: order, icon: "" });
            });
            console.log("saving");
            (new Ajax_1.Ajax()).put("/menu/" + this.menuID, { links: linksToSave, isAjax: true }, function (e) {
                if (e.success)
                    window.location.href = e.redirect;
            });
            return false;
        };
        MenuEditor.LINKHTML = LinkTemplate_1.template;
        return MenuEditor;
    }());
    exports.MenuEditor = MenuEditor;
});
//# sourceMappingURL=MenuEditor.js.map