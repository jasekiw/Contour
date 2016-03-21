<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/22/2015
 * Time: 9:16 AM
 */
?>


<div class="panel panel-default" id="DatablockEditor">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Block</h3>
        <a class="exitButton" href="javascript:main.dataBlockEditor.exit()" ><i class="fa fa-times"></i></a>
    </div>
    <div class="panel-body">
        <div class="top_section">
            <input type="text" name="datablock_value"/>
            <input type="button" name="calculate" value="Calculate" />

        </div>
        <div class="bottom_section">
            <div class="calculated">

            </div>
            <div class="options">
                <input type="submit" value="Save" class="submit" />
            </div>
        </div>
    </div>
</div>
