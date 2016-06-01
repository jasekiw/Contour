/**
 * Created by Jason Gallavin on 3/25/2016.
 */

export class ExcelImportPage
{
    protected importing : boolean = false;
    constructor()
    {
        $(".importButton").on("click", () => this.uploadExcelFile());
    }

    public uploadExcelFile() : void
    {
        let data = new FormData();
        let input : HTMLInputElement = <HTMLInputElement> $('.btn-file input')[0];
        if (input.files.length == 0) {
            alert("You must select an excel file to upload first");
            return;
        }
        data.append("excelFile", input.files[0]);
        jQuery.ajax({
            url:         '/import/upload',
            data:        data,
            cache:       false,
            contentType: false,
            processData: false,
            dataType:    'json',
            type:        'POST',
            success:     (data : {success : boolean; message : string}) =>
                         {
                             if (!data.success)
                                 alert("upload failed");
                             else
                                 this.startImport();
                         }
        });

    }

    public startImport() : void
    {
        let data = new FormData();
        let suiteName : string = $("#suite").val();
        data.append("suite", suiteName);
        let importLocation : string = $("#importLocation").find("input").val();
        data.append("importLocation", importLocation);
        let progress = $(".progress");
        progress.text("Import started...");
        progress.show();
        jQuery.ajax({
            url:         '/import/start',
            data:        data,
            cache:       false,
            contentType: false,
            processData: false,
            type:        'POST',
            timeout:     1200000,
            complete:    (jqXHR : JQueryXHR) => {
                progress.html(jqXHR.responseText);
                this.importing = false;
            },
            error: (jqXHR : JQueryXHR) => progress.html("ERROR: " + jqXHR.responseText)
        });
        this.importing = true;
        setTimeout(() => this.checkProgress(), 10000);
    }
    public checkProgress()
    {
        let progress = $(".progress");
        jQuery.ajax({
            url:         '/import/check',
            contentType: false,
            processData: false,
            type:        'GET',
            timeout:     10000,
            complete:    (jqXHR : JQueryXHR) => this.appendProgress(jqXHR.responseText),
            error:       (jqXHR : JQueryXHR) => progress.html("ERROR: " + jqXHR.responseText)
        });
    }

    protected appendProgress(data : string)
    {
        if(data.indexOf("/") == -1)
            return;
        let split = data.split('/');
        let current = parseInt(split[0]);
        let max = parseInt(split[1]);
        if(current >= max)
        {
            this.importing = false;
            return;
        }
        $(".progress").html(data);
        if(this.importing)
            setTimeout(() => this.checkProgress(), 5000);
    }

}
