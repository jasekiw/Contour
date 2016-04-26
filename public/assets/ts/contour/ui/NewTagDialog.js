define(["require", "exports", "./templates/NewTagDialogTemplate", "../api/Types", "../api/Tags"], function (require, exports, NewTagDialogTemplate_1, Types_1, Tags_1) {
    "use strict";
    /**
     * The dialog used to create a new tag
     */
    var NewTagDialog = (function () {
        function NewTagDialog() {
            var _this = this;
            this.id = "createTag_" + (new Date().getTime().toString());
            var template = NewTagDialogTemplate_1.template.replace("[id]", function () { return _this.getId(); });
            $("body").append(template);
            $("body").append(NewTagDialogTemplate_1.style);
            this.element = $("#" + this.id);
            this.form = this.element.find("form");
            this.form.find('[type="submit"]').click(function (e) { return _this.submit(e); });
            $('.create_ui form .cancel').click(function (e) {
                e.preventDefault();
                _this.hide();
            });
        }
        /**
         *
         * @param onSubmit
         * @param parentId
         * @param types
         * @param x
         * @param y
         */
        NewTagDialog.prototype.show = function (onSubmit, parentId, types, x, y) {
            if (types === void 0) { types = "all"; }
            this.onSubmit = onSubmit;
            this.form.find('input[name="parent_id"]').val(parentId);
            this.form.find('input[name="name"]').val("");
            if (types == "all")
                this.populateTypes();
            else {
                this.form.find('select[name="type"]').hide();
                this.form.find('select[name="type"]').html(this.getTypeOption(types));
            }
            var middleX = $(window).width() / 2;
            var middleY = $(window).height() / 2;
            this.element.css("right", '');
            this.element.css("left", '');
            this.element.css("top", '');
            this.element.css("bottom", '');
            this.element.css('position', 'absolute');
            if (x > middleX)
                this.element.css("right", $(window).width() - x);
            else
                this.element.css("left", x);
            if (y > middleY)
                this.element.css("bottom", $(window).height() - y);
            else
                this.element.css("top", y);
            this.element.show();
        };
        NewTagDialog.prototype.getId = function () {
            return this.id;
        };
        NewTagDialog.prototype.populateTypes = function () {
            var _this = this;
            Types_1.Types.getTagTypes(function (e) {
                var selectOptions = "";
                e.payload.forEach(function (type) {
                    selectOptions += "<option value=\"" + type.name + "\" >" + type.name + "</option>\r\n";
                });
                _this.form.find('select[name="type"]').html(selectOptions);
            });
        };
        NewTagDialog.prototype.getTypeOption = function (type) {
            return "<option value=\"" + type + "\" >" + type + "</option>\r\n";
        };
        NewTagDialog.prototype.submit = function (e) {
            e.preventDefault();
            var url = this.form.attr("action");
            var name = this.form.find('[name="name"]').val();
            var parentId = parseInt(this.form.find('[name="parent_id"]').val());
            var type = this.form.find('[name="type"]').val();
            Tags_1.TagsApi.create(name, parentId, type, this.onSubmit);
            this.hide();
            return false;
        };
        NewTagDialog.prototype.hide = function () {
            this.element.hide();
        };
        return NewTagDialog;
    }());
    exports.NewTagDialog = NewTagDialog;
});
//# sourceMappingURL=NewTagDialog.js.map