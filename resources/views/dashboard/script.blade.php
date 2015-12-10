<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/14/2015
 * Time: 3:24 PM
 */
        ?>

<script type="text/javascript">
//    $(".dropzone").dropzone({
//        url: "php/dropzone-upload.php",
//        addRemoveLinks : true,
//        maxFilesize: 50,
//        maxFiles: 5,
//        acceptedFiles: 'image/*, application/pdf, .txt',
//        dictResponseError: 'File Upload Error.'
//    });

    (function ($) {

        $(document).ready(function () {
            $(".item_button").height($(".item_title").outerHeight());
        });

        $(window).resize(function () {
            $(".item_button").height($(".item_title").outerHeight());
        });

    })(jQuery);
</script>
