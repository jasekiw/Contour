<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/9/2015
 * Time: 11:59 PM
 */

        ?>


<script type="text/javascript">
    $(".tag.has_menu").contextmenu();
    $(".context_menu_item").click(function()
    {
        console.log("activating menu item");
        window.location.href = $(this).attr('href');

    });

    var editor = new TagEditor();



</script>
