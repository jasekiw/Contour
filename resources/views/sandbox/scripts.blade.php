<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 11/4/2015
 * Time: 2:04 AM
 */

        ?>

<script type="text/javascript">
//        var datablockEditor = new DatablockEditor();
//        datablockEditor.show(518);
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
                dynamicEditor.find("input[type='submit']").on("click", function(e) {
                        console.log("test");
                        if(inputName.val() != "" && inputValue.val() != "")
                        {
                                form.append('<input type="text" value="' + inputValue.val() + '" name="' + inputName.val() + '" />');
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

                        $.ajax("/api/getValue", {
                                type: "POST",
                                data: submission,
                                dataType: "json",
                                success: function(e)
                                {
                                        console.log("got a response");
                                        console.log(e);
                                }

                        });

                });



        }
        dynamicEditor();
</script>
