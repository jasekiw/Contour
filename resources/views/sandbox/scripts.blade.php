<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 11/4/2015
 * Time: 2:04 AM
 */
use app\libraries\theme\userInterface\DataBlockEditor;
        ?>
<?php
echo DataBlockEditor::get();
?>

<script type="text/javascript">
        //var dataBlockEditor = new DataBlockEditor();
        //dataBlockEditor.open( $('input[name=test]'), 11, "#(EG__OP_Summary/Total_Revenues, Jan)");
</script>

<script type="text/javascript">

        $.fn.serializeObject = function()
        {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function() {
                        if (o[this.name] !== undefined) {
                                if (!o[this.name].push) {
                                        o[this.name] = [o[this.name]];
                                }
                                o[this.name].push(this.value || '');
                        } else {
                                o[this.name] = this.value || '';
                        }
                });
                return o;
        };

        function dynamicEditor()
        {

            var form = $("#dynamic_submission");
                var dynamicEditor = $(".dynamic_editor");
                var inputName = dynamicEditor.find("input[name='name']");
                var inputValue = dynamicEditor.find("input[name='value']");
                dynamicEditor.find("input[name='add_attribute']").on("click", function(e) {

                        if(inputName.val() != "" && inputValue.val() != "")
                        {
                                form.append('<input type="text" value="' + inputValue.val() + '" name="' + inputName.val() + '" />');
                        }
                });
                var inputUrl = dynamicEditor.find("input[name='request']");
                dynamicEditor.find("input[name='change_request']").on("click", function(e) {

                        if(inputUrl.val() != "")
                        {
                                form.attr("action", inputUrl.val());
                        }
                });

                form.submit(function(e) {
                        e.preventDefault();
                        console.log(e);
                        var submission = {};
                        var inputs = form.find("input:not('[type=submit]')");
                        submission = form.serializeObject();
//                        inputs.each(function(element)
//                        {
//                                submission[$(element).attr("name")] = $(element).attr("value");
//
//                        });

                        $.ajax(form.attr("action"), {
                                type: "POST",
                                data: submission,
                                dataType: "json",
                                success: function(e)
                                {
                                        console.log("got a response");
                                        console.log(e);
                                        $("#result").val(JSON.stringify(e));
                                },
                                error: function(e){
                                       console.log(e);
                                        $('.error_output').html(e.responseText);
                                       // $("#result").val(e);
                                }

                        });

                });



        }
        dynamicEditor();

</script>
