/**
 * Created by Jason Gallavin on 9/11/2015.
 */


function ExcelTableBuilder(TagID, container, url, statusURL, resetURL) {
    this.tag = TagID;
    this.container = container;
    this.url = url;
    this.statusURL = statusURL;
    this.loading = false;
    this.resetURL = resetURL;
    var _self = this;
    this.maxValue = 0;
    this.currentValue = 0;
    this.dataToRender = "";

    this.run = function()
    {
        _self.loading = true;
        $.get(_self.url, _self.loadingCompleted);
        _self.updateStatus();
    };
    this.updateStatus = function()
    {
        if(_self.loading)
        {
            $.get(_self.statusURL, function(data, status){
                if(data != "")
                {
                    var loadingbarvalues = data;
                    var curentLoad = data.substring(0, data.indexOf('/'));
                    var max = data.substring( (data.indexOf('/') + 1));
                    _self.currentValue = curentLoad;
                    _self.maxValue = max;
                    _self.updateLoadingBar();
                    console.log(data);
                }

                setTimeout(_self.updateStatus, 1000);

            });

        }

    };


    this.updateLoadingBar = function()
    {
        var percentage = Math.round((_self.currentValue / _self.maxValue) * 100);
        var progressClass = '.progress';
        var progressBar = '.progress .progress-bar';
        if($(progressClass).length == 0)
        {
            var loadingBar = '<div class="progress">\
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="' + _self.maxValue + '">\
                        ' + percentage + '%\
                        </div>\
                        </div>';
            $(_self.container).html(loadingBar);
        }
        $(progressBar).attr('aria-valuemax',_self.maxValue);
        $(progressBar).attr('aria-valuenow',_self.currentValue);
        $(progressBar).css('width', percentage + '%');
        $(progressClass).css('margin-top', '200px');
        $(progressBar).text( percentage + '%');
    };
    this.loadingCompleted = function(data, status)
    {
        _self.loading = false;
        _self.currentValue = _self.maxValue;
        _self.updateLoadingBar();
        _self.dataToRender = data;
        $.get(_self.resetURL);
        setTimeout(_self.renderData, 500);

    };

    this.renderData = function()
    {
        $(_self.container).html(_self.dataToRender);
    }
}