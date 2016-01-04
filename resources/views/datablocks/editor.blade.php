<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/22/2015
 * Time: 9:16 AM
 */
?>


<style type="text/css">
    #DatablockEditor {
        display:none;
        overflow-y:scroll;
        box-shadow: 0 0 20px rgba(0,0,0,1)
    }
    #DatablockEditor .options {
        margin:10px;
        float:right;
    }
    #DatablockEditor .panel-title {
        display: inline-block;
    }
    #DatablockEditor .exitButton{
       float:right;
    }

    #DatablockEditor .top_section {
        padding: 20px;
    }

    #DatablockEditor .top_section [name=datablock_value] {
        width: 100%;
        height: 50px;
    }

    #DatablockEditor .bottom_section {
        padding: 20px;
    }

    #DatablockEditor .datablock_view {
        border: 1px solid black;
        min-height: 100px;
        margin-top: 40px;
    }
</style>



<div class="panel panel-default" id="DatablockEditor">
    <div class="panel-heading">
        <h3 class="panel-title">Datablock Editor</h3>
        <a class="exitButton" href="javascript:main.dataBlockEditor.exit()" >X</a>
    </div>
    <div class="panel-body">
        <div class="top_section">
            <input type="text" name="datablock_value"/>
            <input type="button" name="calculate" value="Calculate Value" />
            <div class="calculated">

            </div>
        </div>
        <div class="bottom_section">
            <div class="datablock_controls">
                <a class="control" href="javascript:main.dataBlockEditor.up()">Up</a>
                <input type="text" name="search"/>
            </div>
            <div class="datablock_view">
                <table class="datablocks">
                    <thead class="header_container">
                    </thead>
                    <tbody class="row_and_datablock_container">
                    </tbody>
                </table>
            </div>
            <div class="options">
                <input type="submit" value="Save" class="btn btn-primary submit" />
                <input type="submit" value="Cancel" class="btn btn-danger cancel" />
            </div>
        </div>
    </div>
</div>
