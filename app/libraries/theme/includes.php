<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/13/2015
 * Time: 1:11 PM
 */

/*
 * Profile Page
 */
Theme::enqueue_script('mockajax', 'assets/js/plugins/bootstrap-editable/jquery.mockjax.min.js', 'profile');
Theme::enqueue_script('inline-editor', 'assets/js/plugins/bootstrap-editable/bootstrap-editable.min.js', 'profile');
Theme::enqueue_script('jquery-ui', 'assets/js/jquery-ui/jquery-ui-1.10.4.custom.js', 'profile');
Theme::enqueue_script('masked-input', 'assets/js/plugins/jquery-maskedinput/jquery.masked-input.min.js', 'profile');
Theme::enqueue_script('dropzone', 'assets/js/plugins/dropzone/dropzone.min.js', 'profile');
Theme::enqueue_script('select2', 'assets/js/plugins/select2/select2.min.js', 'profile');

Theme::enqueue_script('drop-zone', 'assets/js/mini_drop_zone/dropzone.js', 'profile');
Theme::enqueue_style('drop-zone', 'css/mini_drop_zone/style.css', 'profile');

/*
 * Dashboard
 */



/*
 * Configuration
 */

Theme::enqueue_script('flash_message', 'assets/js/plugins/jquery-gritter/jquery.gritter.min.js');


/**
 * Tag Browser
 */

Theme::enqueue_script('context', 'assets/js/plugins/bootstrap-contextmenu/bootstrap-contextmenu.js', 'Tag Browser');


Theme::enqueue_script('loadexcel', 'assets/js/excel/ExcelTableBuilder.js', 'Excel Editor');

Theme::enqueue_script('progressBar', 'assets/js/plugins/bootstrap-progressbar/bootstrap-progressbar.min.js', 'Excel Editor');

Theme::enqueue_script('tag_editor', 'assets/js/tag_editor/TagEditor.js', 'Tag Browser');
Theme::enqueue_script('jquery-ui', 'assets/js/jquery-ui/jquery-ui-1.10.4.custom.js', 'Tag Browser');


