/**
 * @author Jason Gallavin
 * @date 3/8/2016
 * @version 0.8
 */

/**
 * Sets the footer to the bottom of the page if page height is less than the window
 * @param {string} footerSelector The footer of the page to move down
 * @param {jQuery} jQ jQuery Object to use
 * @constructor
 */
function FullHeight(footerSelector, jQ)
{
    if(footerSelector == undefined)
    {
        console.log("selector not given");
        return;
    }
    if (jQ == undefined)
    {
        console.log("jQuery not given");
        return;
    }
    $ = jQ.noConflict();

    var adjustFooterToScreen = function()
    {
        $('body').css("height", "");
        var windowHeight = $( window ).height() - 6 ;
        var bodyHeight = $('body').outerHeight();

        if(bodyHeight < windowHeight)
            $('body').css("height", windowHeight);
        var wpAdminOffset = parseInt($('html').css('margin-top').replace("px", ""));
        if(wpAdminOffset != undefined && wpAdminOffset > 0)
            windowHeight -= (wpAdminOffset - 1);

        if(bodyHeight < windowHeight)
            $(footerSelector).css("position", "fixed").css("width", "100%").css("bottom", "0");
        else
            $(footerSelector).css("position", "static");
    };

    $(window).load(function() {

        adjustFooterToScreen();
        $(window).on("resize", adjustFooterToScreen );
    });
}
//var resizer = new FullHeight("#socket", jQuery); // example