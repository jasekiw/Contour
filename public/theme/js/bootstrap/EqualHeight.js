/**
 * Created by Jason Gallavin on 10/5/2015.
 */

    equalheight = function(container, offset){

        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;
        $(container).each(function() {

            $el = $(this);
            $($el).height('auto')
            topPostion = $el.position().top;

//                        if (currentRowStart != topPostion) {
//                            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
//                                rowDivs[currentDiv].height(currentTallest);
//                            }
//                            rowDivs.length = 0; // empty the array
//                            currentRowStart = topPostion;
//                            currentTallest = $el.height();
//                            rowDivs.push($el);
//                        }
            {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height() + offset) : (currentTallest + offset);
            }
            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }

    $(window).load(function() {

        equalheight('#event_wrapper .row .eventContent', $("#register_link-11").height() + 40 );
        equalheight('#event_wrapper h3[id*="event_title-"]', 0 );
        changeDescription();
    });


    $(window).resize(function(){
        equalheight('#event_wrapper .row .eventContent',  $("#register_link-11").height() + 40 );
        equalheight('#event_wrapper h3[id*="event_title-"]', 0 );
        changeDescription();

    });



