/**
 * Created by Jason on 8/17/2015.
 */

function DropZone($element, post_url, upload_id, target, loadedfunction) {

    this.$dropzone = $($element);
    this.$img = $($element + " img");
    this.$input = $($element + " input");
    this.$div = $($element + " div");
    this.post_url = post_url;
    this.upload_id = upload_id;
    this.target = target;
    this.loadedfunction = loadedfunction;
    var _self = this;



            //dragover
        this.$dropzone.on('dragover', function() {
            $(this).addClass('hover');
        });
            //dragleave
        this.$dropzone.on('dragleave', function() {
            $(this).removeClass('hover');
        });




        this.$dropzone.on('change', function(e) {
            // gets the first file in the input box
            console.log(_self.$input.get(0));
            var file = _self.$input.get(0).files[0];

            //removes the hover class
            _self.$dropzone.removeClass('hover');

            //if the file is not allowed alert the page that it is not an accepted file type
            if (this.accept && $.inArray(file.type, this.accept.split(/, ?/)) == -1) {
                return alert('File type not allowed.');
            }
            //add the css class dropped so that css can update the style
            _self.$dropzone.addClass('dropped');
            //remove the image element from the drop zone
            _self.$img.remove();

            //creates a regular expression to see if any of the file extensions are in the file
            if ((/^image\/(gif|png|jpeg)$/i).test(file.type)) {
                var formdata = new FormData();
                var reader = new FileReader();
                reader.readAsDataURL(file);

                if (formdata) {
                    formdata.append(_self.upload_id, file);
                    formdata.append('name', _self.upload_id);
                }


                if (formdata) {
                    $.ajax({
                        url: _self.post_url,
                        type: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: _self.onSucess
                    });
                }
            }
        });


        this.upload = function upload(event)
        {
            var result = event.target.result;
            var fileName = document.getElementById('fileBox').files[0].name; //Should be 'picture.jpg'
            $.post(this.post_url, { value: result, filename: fileName, name: 'file' }, this.onSucess);
        };

        this.onSucess = function onSucess(event) {

            if(typeof _self.target != 'undefined')
            {
                if($(_self.target).prop("tagName") == "IMG")
                {
                    $(_self.target).attr('src', event);
                }
                else{
                    $(_self.target).html(event);
                }

            }
            if(typeof _self.loadedfunction != 'undefined')
            {
                setTimeout(function() {
                    _self.loadedfunction(_self.target);
                }, 200)

            }

        };





}

