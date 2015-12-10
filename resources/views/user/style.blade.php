<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/14/2015
 * Time: 10:18 AM
 */
?>

<style type="text/css">
    .profile_pic_container {
        position:relative;
    }
    #profile_pic_dropzone {
       position:fixed;
        left: 5%;
        top: 5%;
        bottom: 5%;
        right: 5%;
        z-index: 500;
        display:none;

    }
    .select2 {
        min-width: 200px;
    }
    #dropzone {
        opacity: 0;
        background: #333;
        -webkit-transition-duration: 0.3s; /* Safari */
        transition-duration: 0.3s;
    }
    #dropzone:hover {
        opacity: .7;
    }
    #dropzone div{
        font-size:14px;
    }
    .mini_dropzone_container {
        overflow-x: hidden;
    }


</style>