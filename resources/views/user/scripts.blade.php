<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/13/2015
 * Time: 3:23 PM
 */
?>
<script type="text/javascript">
        $('.editable').editable();
        $('.editable-click').click(function(){

                setTimeout(function(){
                        setDraggable('.popover');
                }, 200);
        });



        function setDraggable(classname)
        {
                $(classname).draggable();
        }




        $('#phone').click(function(){

                setTimeout(function(){
                        mask('#phone', "(999) 999-9999");
                }, 200);
        });

        function mask($input, $mask)
        {
                $($input).next('.popover').find('.editable-input input').mask($mask);
        }



        $('#gender').click(function(){

                setTimeout(function(){
                        changeToSelect('#gender', 'gender');
                }, 100);
        });

        function changeToSelect($input, $id)
        {
                $($input).next('.popover').find('.editable-input input').after('<select class="select2 select_input">' +
                        '<option value=""></option>' +
                        '<option value="Male"> Male </option>' +
                        '<option value="Female"> Female </option>' +
                        '<option value="None"> None </option>' +
                        '</select>');

                $('.popover-content .form-control').hide();
                var original = $('.popover-content .form-control').val();
                console.log(original);
                var $eventSelect = $(".popover-content .select_input");
                var selection = '.popover-content .select_input option[value*="'+ original +  '"]';
                console.log(selection);
                $('.editable-clear-x').hide();
                $('.popover-content .select_input option[value*="'+ original +  '"]').attr('selected', 'true');
                $('.popover-content .select2').select2();
                $eventSelect.on("change", function (){

                        $('.popover-content .form-control').val( $(this).val() );
                });

        }
        initialize_drop_zone('#profile_picture');
        var dropzone = new DropZone('#dropzone', 'http://localhost/profile/save', 'profile_pic', '#profile_picture', initialize_drop_zone);

        function initialize_drop_zone(target)
        {
                var left = $(target).position().left;
                var top = $(target).position().top;
                var width =  $(target).width();
                var height =  $(target).height();
                $('.mini_dropzone_container').css("position", "absolute");
                $('.mini_dropzone_container').css("overflow", "hidden");
                $('.mini_dropzone_container').css("margin-top", 0);
                $('.mini_dropzone_container').css("margin-bottom", 0);
                $('.mini_dropzone_container').css("top", top - 5);
                $('.mini_dropzone_container').css("left", left - 5 );

                $('#dropzone').css("margin-top", 0);
                $('#dropzone').css("margin-bottom", 0);
                $('#dropzone').css("width", width + 10);
                $('#dropzone').css("height", height + 10);

        }


</script>