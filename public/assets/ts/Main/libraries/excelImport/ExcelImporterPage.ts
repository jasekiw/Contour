/**
 * Created by Jason Gallavin on 3/25/2016.
 */



class ExcelImportPage {
    constructor()
    {
        $(".importButton").on("click", () => this.uploadExcelFile());
    }

    public  uploadExcelFile() : void {
        let data = new FormData();
        let input : HTMLInputElement = <HTMLInputElement> $('.btn-file input')[0];
        if(input.files.length == 0) {
            alert("You must select an excel file to upload first");
            return;
        }
        data.append("excelFile",input.files[0]);
        jQuery.ajax({
            url: '/import/upload',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            type: 'POST',
            success: (data : {success : boolean; message: string} ) => {
                if(!data.success)
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
        data.append("importLocation",importLocation );
        let progress = $(".progress");
        progress.text("Import started...");
        progress.show();
        jQuery.ajax({
            url: '/import/start',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            timeout: 1200000,
            complete: (jqXHR : JQueryXHR) => progress.html(jqXHR.responseText),
            error: ( jqXHR : JQueryXHR) =>  progress.html("ERROR: " + jqXHR.responseText)
        });
    }


}
