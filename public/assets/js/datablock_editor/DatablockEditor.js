/**
 * Created by Jason on 11/4/2015.
 */


function DatablockEditor() {

    var datablockInterfacer = new DataBlockInterfacer();
    var datatagInterfacer = new DatatagInterfacer();

    var dataBlockEditor = $("#DatablockEditor");
    var dataBlockContainer = dataBlockEditor.find(".datablocks");
    var head = dataBlockEditor.find(".header_container");
    var body = dataBlockEditor.find(".row_and_datablock_container");
    var searchBar = dataBlockEditor.find("[name=search]");

    searchBar.change( function() {
        filterView(searchBar.val());
    });
    /**
     * Clears the the datablocks our from the container
     */
    var clearDatablocks = function()
    {
        dataBlockContainer.html("");
    };

    var addDataBlock = function(data)
    {
        body.append(data);
    };

    var addtoHead = function(data)
    {
        head.append(data);
    };

    var removeDatablocks = function()
    {
        body.find(".datablock").remove();
    };

    var removeHeadTags = function()
    {
        head.html("");
    };

    var removeRowTags = function()
    {
        body.find(".rowTag").remove();
    };

    var filterView = function(filterText)
    {
        console.log("filtering for" + filterText);
    };

    /**
     * Show the editor with the current sheet it
     * @param id
     */
    this.show = function(id)
    {
        datatagInterfacer.getChildrenRecursive(id, populateEditor );

        dataBlockEditor.show();
    };
    var populateEditor = function(data)
    {
        var thisfunction = this;
       // console.log(data);
        if(data.success)
        {
            removeHeadTags();
            removeRowTags();
            data.tags.forEach(function(element, index)
            {
                var dataToAppend = "<tr class='rowTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' ><td class='tag'>" + element.name +  "</td></tr>";

                thisfunction.element = element;
                if(element.type.toUpperCase() == "COLUMN")
                {
                    head.append("<th class='headTag' tagId='" + element.id + "' sort_number='" + element.sort_number + "' >" + element.name +  "</th>");
                }
                else if (element.type.toUpperCase() == "ROW") // the element to add is a row tag
                {
                    var numberOfRows = body.find(".rowTag").length; // get number of rows

                    if(body.find(".rowTag").length > 0) // if there are rows
                    {

                        if(body.find(".rowTag").last().attr("sort_number") < element.sort_number)
                        {
                            body.find(".rowTag").last().after(dataToAppend);
                        }
                        else if(body.find(".rowTag").first().attr("sort_number") > element.sort_number)
                        {
                            body.find(".rowTag").last().before(dataToAppend);
                        }
                        else
                        {


                            setTimeout(function() {cicleThroughandAdd(".rowTag",dataToAppend, element );}, 1); //fake multithreading
                        }

                    }
                    else
                    {
                        body.append(dataToAppend);
                    }


                }

            });

        }
        else
        {
            window.alert("something went wrong when pupulating the editor");
        }
    };



    var cicleThroughandAdd = function(classtoLoop,dataToAppend,  element)
    {
        var numberOfRows = body.find(".rowTag").length;
        var thisfunction = this;
        this.element = element;
        body.find(classtoLoop).each(function(index) //loops through each row
        {
            var sortNumber =  parseInt($(this).attr("sort_number"));
            if((numberOfRows - 1) > index && sortNumber > thisfunction.element.sort_number) // if not last and row's sort is grater than element to add
            {

                $(this).before(dataToAppend);
                return false;

            }
            else if((numberOfRows - 1) == index)
            {

                if(sortNumber > thisfunction.element.sortNumber)// if row's sort number is greater than the one to add
                {
                    $(this).before(dataToAppend);
                    return false;
                }
                else
                {
                    $(this).after(dataToAppend);
                    return false;
                }

            }



        });
    };


    var populate_datablocks = function()
    {

    }






}
