var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
define(["require", "exports", "./templates/NewTagDialogTemplate", "../api/Types", "../api/Tags", "./Dialog"], function (require, exports, NewTagDialogTemplate_1, Types_1, Tags_1, Dialog_1) {
    "use strict";
    /**
     * The dialog used to create a new tag
     */
    var NewTagDialog = (function (_super) {
        __extends(NewTagDialog, _super);
        function NewTagDialog() {
            _super.call(this, "Create", "Create", NewTagDialogTemplate_1.content, "/tags/create");
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
            _super.prototype._show.call(this, onSubmit, x, y);
            this.form.find('input[name="parent_id"]').val(parentId);
            this.form.find('input[name="name"]').val("");
            if (types == "all")
                this.populateTypes();
            else {
                this.form.find('select[name="type"]').hide();
                this.form.find('select[name="type"]').html(this.getTypeOption(types));
            }
            this.element.show();
        };
        /**
         * grabs all tag types and puts them into the select menu
         */
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
        /**
         * returns a string of select options of the givenh string
         * @param {string} type
         * @returns {string}
         */
        NewTagDialog.prototype.getTypeOption = function (type) {
            return "<option value=\"" + type + "\" >" + type + "</option>\r\n";
        };
        /**
         * Called when the user submits the form. The Method onSubmit will be called with the tag data to be handled outside this class
         * @param {JQueryEventObject} e
         */
        NewTagDialog.prototype.submit = function (e) {
            e.preventDefault();
            var url = this.form.attr("action");
            var name = this.form.find('[name="name"]').val();
            var parentId = parseInt(this.form.find('[name="parent_id"]').val());
            var type = this.form.find('[name="type"]').val();
            Tags_1.TagsApi.create(name, parentId, type, this.onSubmit);
            this.hide();
        };
        NewTagDialog.prototype.hide = function () {
            this.element.hide();
        };
        return NewTagDialog;
    }(Dialog_1.DialogBox));
    exports.NewTagDialog = NewTagDialog;
});
//# sourceMappingURL=NewTagDialog.js.map