<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/10/2015
 * Time: 1:55 AM
 */
        ?>
<script type="text/javascript">
//    var wrapperheight = $(".wrapper").height();
//    $(".wrapper > .bottom").height(wrapperheight - $(".footer").height());
    $(".excel_editor_container").height($(window).height() - 300);
    var table = new ExcelTableBuilder('<?php echo $sheet->get_id() ?>',
            '.excel_editor_container',
            '<?php echo URL::action('AjaxExcelController@get', [$sheet->get_id()])?>',
            '<?php echo route('status_ajax_excel'); ?>',
            '<?php echo route('reset_ajax_excel'); ?>');
    //table.run();
</script>
