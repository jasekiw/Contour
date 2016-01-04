/**
 * Created by Jason Gallavin on 9/23/2015.
 */

/**
 *
 * Tag Editor
 * Require Jquery UI Draggable
 *
 *
*/

function TagEditor()
{
    var _self = this;
    var renameUI =
        '<div class="tag_editor rename_ui">\
            <h3 class="title">Rename</h3>\
            <form method="POST" action="/ajax/tageditor/rename">\
                <input type="text" value="name" class="form-control name" />\
                <input type="checkbox" name="recursive" value="true">Also rename other tags with the same name.\
                <input type="submit" value="Cancel" class="btn btn-danger cancel" />\
                <input type="submit" value="Save" class="btn btn-primary submit" />\
            </form>\
        </div>\
    ';

    this.mouseX = 0;
    this.mouseY = 0;
    var TagTypes = null;





    $(function()  // wheb page is finished loading, start the editor javascript
    {
        _self.initialize();
    });

    this.initialize = function()
    {
        $('head').append('<link href="/assets/js/tag_editor/tageditor.css" rel="stylesheet" />');

        $('body').append(renameUI);

        $.get("/assets/js/tag_editor/delete_ui.html", function(e)
        {
            $('body').append(e);
            $.get("/assets/js/tag_editor/create_ui.html", function(e)
            {
                $('body').append(e);
                handleRenameEvents();
                handleDeleteEvents();
                handleCreateEditor();
                $('.tag_editor').draggable();
            });

        });

       getTagTypes();

        trackMouse();


        handleSelectTags();
        drag_rearrange();
        setup_droppable();

    };



    var handleDeleteEvents = function()
    {

        var $delete_form = $('.delete_ui form');
        $delete_form.find(".submit").click(
            function(e)
            {
                e.preventDefault();
                var url = $delete_form.attr("action");
                var data = { id:  $(".delete_ui form").find('input[name=id]').val() };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (e)
                    {
                        if(e.success)
                        {
                            $(".tag_" +  $(".delete_ui form").attr('tag_id')).parent().remove();
                        }
                    },
                    dataType: "json"
                });


                $('.delete_ui').hide();
                return false;
            });
        $('.delete_ui form .cancel').click(function(e)
        {
            e.preventDefault();
            $('.delete_ui').hide();
        });
    };


    this.rename = function(id)
    {

        getRenameEditor(id, $(".tag_" + id).text() );
    };

    var getRenameEditor = function(id, name)
    {
        var $ui =  $(".rename_ui");
        $(".rename_ui .name").val(name);
        console.log(name);
        $(".rename_ui form").attr('action', '/ajax/tageditor/' + id );
        $(".rename_ui form").attr('tag_id', id );
        $ui.css('position', 'absolute');
        $ui.css("left", _self.mouseX);
        $ui.css("top",  _self.mouseY);
        $ui.show();
    };

    var getDeleteEditor = function(id)
    {
        var $ui =  $(".delete_ui");
        $(".delete_ui form").attr('action', '/tags/delete');
        $(".delete_ui form").attr('tag_id', id );
        $(".delete_ui form").find('input[name=id]').val(id);
        $ui.css('position', 'absolute');
        $ui.css("left", _self.mouseX);
        $ui.css("top",  _self.mouseY);
        $ui.show();
    };
    this.create = function() {
        getCreateEditor()
    };

    var handleCreateEditor = function()
    {
        var $create_form = $('.create_ui form');
        $create_form.find(".submit").click(
            function(e)
            {
                e.preventDefault();
                var url = $create_form.attr("action");
                var data =  $create_form.serialize() ;
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (e)
                    {

                        if(e.success)
                        {
                            $(".tags").append('<li class="ui-droppable"><a class="tag has_menu tag_' + e.id + ' context type_folder ui-draggable" data-toggle="context" data-target="#contextMenu93" href="http://localhost/tags/' + e.id + '">' + e.name + '</a></li>');

                        }
                    },
                    dataType: "json"
                });


                $('.create_ui').hide();
                return false;
            });
        $('.create_ui form .cancel').click(function(e)
        {
            e.preventDefault();
            $('.create_ui').hide();
        });
    };

    var getCreateEditor = function()
    {
        var $ui =  $(".create_ui");
        var $uiForm = $ui.find("form");
        var parentId = getTagId( $(".tags") );
        console.log("parent_ID: " + parentId);
        $uiForm.find('input[name=parent_id]').val( getTagId( $(".tags") ) );
        $uiForm.find('input[name=name]').val("");
        $uiForm.find('select[name=type]').html(TagTypes);

        $ui.css('position', 'absolute');
        $ui.css("left", _self.mouseX);
        $ui.css("top",  _self.mouseY);
        $ui.show();

    };
    var getTagTypes = function()
    {

         $.get("/tags/types", function(e) {
             TagTypes = e;

        });
    };

    var handleRenameEvents = function()
    {
        var $rename_form = $('.rename_ui form');
        $rename_form.find(".submit").click(function(e)
        {
            e.preventDefault();

            $.post( $rename_form.attr("action"), { command: "rename", name: $rename_form.find(".name").val() }).done(function(data)
            {
                $(".tag_" +  $(".rename_ui form").attr('tag_id')).text(data);

            });
            $('.rename_ui').hide();
        });
        $('.rename_ui form .cancel').click(function(e)
        {
            e.preventDefault();
            $('.rename_ui').hide();
        });
    };
    this.delete = function(id)
    {
        getDeleteEditor(id);
    };


    var trackMouse = function()
    {
        $( document ).on( "mousemove", function( event ) {
            _self.mouseX = event.pageX;
            _self.mouseY = event.pageY;

            $(".console").text("x: " + event.pageX + " Y: " + event.pageY);
        });
    };

    var drag_rearrange = function()
    {
        $(".tag:not(.excluded)").draggable( {
            distance: 15,
            drag: function(e)
            {

                var droppable = $("li.hover");
                var isSource = droppable.find(".source").length > 0;
                if(droppable.length > 0 && !isSource)
                {
                    var left = droppable.offset().left;
                    var width = droppable.width();
                    var middle = left + (width / 2);
                    var oneFourth = width / 4;
                    if(e.pageX > middle)
                    {
                        if(e.pageX > (middle + oneFourth))
                        {
                            if(!droppable.hasClass("dropRight")) // going to be dropped to the right
                            {
                                droppable.addClass("dropRight");
                                droppable.removeClass("dropLeft");
                                droppable.removeClass("dropIn");
                            }
                        }
                        else // hovering this element
                        {
                            if(!droppable.hasClass("dropIn"))
                            {
                                droppable.addClass("dropIn");
                                droppable.removeClass("dropLeft");
                                droppable.removeClass("dropRight");
                            }
                        }

                    }
                    else
                    {
                        if(e.pageX < (middle - oneFourth)) // on the left side
                        {
                            if(!droppable.hasClass("dropLeft"))
                            {
                                droppable.addClass("dropLeft");
                                droppable.removeClass("dropRight");
                                droppable.removeClass("dropIn");

                            }
                        }
                        else // hovering this element
                        {
                            if(!droppable.hasClass("dropIn"))
                            {
                                droppable.addClass("dropIn");
                                droppable.removeClass("dropLeft");
                                droppable.removeClass("dropRight");
                            }
                        }
                    }
                }


            },
            cursor: 'move',
            helper: function(e)
            {

                var helperList = $('<ul class="draggable-helper" />');
                if($(".selected").length > 0 && !$(this).hasClass("selected") && !e.ctrlKey)
                {
                    $(".selected").removeClass("selected");
                    $(this).addClass("selected");
                }

                if( !$(this).hasClass("selected"))
                {
                    $(this).addClass("selected");
                }
                $(".tags .tag.selected").addClass("source");
                helperList.append( $(".tag.selected").clone() );
                helperList.find(".selected").removeClass("selected");
                helperList.find(".source").removeClass("source");
                helperList.css("width", ($(".wrapper").width() / 2) + "px");

                return helperList;
            },
            stop: function(e, ui)
            {


            }
        });
    };

    var setup_droppable = function()
    {
        $(".tags li").droppable({
           accepts: '.tag',
            over: function(e)
            {

                if( !$(this).hasClass("hover"))
                {
                    $(this).addClass("hover");
                }
            },
            out: function(e)
            {
                if( $(this).hasClass("hover"))
                {
                    $(this).removeClass("hover");
                    $(this).removeClass("dropRight");
                    $(this).removeClass("dropLeft");
                    $(this).removeClass("dropIn");
                }
            },
            drop: function(event, ui)
            {

                var draggable = ui.draggable;
                if($(".dropIn, .dropRight, .dropLeft").length == 0)
                {
                    $(".source").removeClass("source");
                }
                else
                {
                    if($(".dropIn").length > 0)
                    {
                        var tagIdstagIds = [];
                        $(".selected").each(function() {
                            tagIdstagIds.push( getTagId( $(this) ) );
                        });
                        var dropin = getTagId( $(".dropIn").find(".tag").first() );



                        $.ajax( {
                                type: "POST",
                                url: '/tags/move',
                                data: {source: tagIdstagIds, target: dropin},
                                success: function(e) {
                                    console.log(e);
                                    console.log(e.success);
                                    if(e.success)
                                    {
                                        console.log("success");
                                        $(".selected").each(function() {
                                            $(this).parent().remove();
                                        });
                                    }
                                },

                                dataType: 'json'
                            }
                        ).fail(function(e) {
                                console.log("fail");
                                console.log(e);
                            });
                        console.log("tag(s): " + tagIdstagIds.toString() + " dropping inside " + dropin);

                    }
                    else if($(".dropLeft").length > 0)
                    {
                        console.log("dropping left")
                    }
                    else if($(".dropRight").length > 0)
                    {
                        console.log("dropping Right")
                    }
                }

                $(this).removeClass("hover");
                $(this).removeClass("dropRight");
                $(this).removeClass("dropLeft");
                $(this).removeClass("dropIn");
            },
            tolerance: "pointer"
        });
    };
    var handleSelectTags = function()
    {

        $('html').click(function() {
            $(".tag").removeClass("selected");
        });

        $(".tag").click(
            function(e)
            {
                $(this).addClass("click"); // adds the click handle

                if(e.ctrlKey) // if ctrl key held
                {
                    if( !$(this).hasClass("selected"))
                    {
                        $(this).addClass("selected");
                    }
                    else
                    {
                        $(this).removeClass("selected");
                    }
                    event.stopPropagation(); // prevents the html from deselecting the items
                    e.preventDefault(); // prevents the mouse from following the link
                    $(".click").removeClass("click"); // remove the click handle
                }
                else if(e.shiftKey) // if shift key held
                {

                    if(!$(this).hasClass("selected") ) // if not selected
                    {
                        if($(".selected").length > 0) // if others are selected
                        {
                            var selectBeginIndex = -1;
                            var selectEndIndex = -1;
                            var clickIndex = -1;
                            $(".tag").each(function (index) // gets information on what is selected to determine direction. sets selection indexes
                            {

                                if ($(this).hasClass("selected") && selectBeginIndex == -1) {
                                    selectBeginIndex = index;
                                }
                                if (!$(this).hasClass("selected") && selectBeginIndex != -1 && index > selectBeginIndex && selectEndIndex == -1) {
                                    selectEndIndex = index - 1;
                                }
                                if ($(this).hasClass("click")) {
                                    clickIndex = index;
                                }


                            });

                            $(".tag").each(function (index) // loops through all of the tags now that we have the selection indexes
                            {

                                if (clickIndex < selectBeginIndex) // if the click is to the left of the selection
                                {
                                    if (index >= clickIndex && index < selectBeginIndex)  // if the current index if within the first selection and the mouse click
                                    {
                                        $(this).addClass("selected");
                                    }
                                }
                                else if (clickIndex > selectEndIndex) // if the click is to the right of the selection
                                {
                                    if (index <= clickIndex && index > selectEndIndex) // if the current index is after the selection and before including the click
                                    {
                                        $(this).addClass("selected");
                                    }
                                }
                            });

                        }
                        else // of others are not selected
                        {
                            $(this).addClass("selected"); // add to selection
                        }
                    }
                    else // if selected
                    {
                        $(this).removeClass("selected"); //removed selection
                    }
                    event.stopPropagation(); // prevents the html from deselecting the items
                    e.preventDefault(); // prevents the mouse from following the link
                    $(".click").removeClass("click"); // remove the click handle
                }// end if modifier keys

            } // click event
        ); // click event
    }; // handleSelectTags

    /**
     *
     * @param tag {jQuery}
     * @returns {Number}
     */
    var getTagId = function(tag) {
        var classvalue = tag.attr("class");
        /**
         * @type {String[]}
         */
        var classnames = classvalue.split(/\s+/);
        for(var i = 0; i < classnames.length; i++)
        {
            if(classnames[i].indexOf("tag_") > -1)
            {
                return parseInt(classnames[i].substring(classnames[i].indexOf("_") + 1, classnames[i].length));
            }
        }
    };

    /**
     * Creates a new tag
     * @param name
     * @param type
     */
    var createTag = function(name, type, parentId)
    {
        $.ajax( {
                type: "POST",
                url: '/tags/create',
                data: {name: name, type: type, parent_id: parentId},
                success: function(e) {

                    console.log(e.success);
                    if(e.success)
                    {
                        $(".tags").append('<li class="ui-droppable"><a class="tag has_menu tag_' + e.id + ' context type_folder ui-draggable" data-toggle="context" data-target="#contextMenu93" href="http://localhost/tags/' + e.id + '">' + e.name + '</a></li>');

                    }
                },

                dataType: 'json'
            }
        ).fail(function(e) {
                console.log("fail");
                console.log(e);
            });
    };

}