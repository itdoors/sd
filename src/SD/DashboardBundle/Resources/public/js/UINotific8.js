 var UINotific8 = function () {
    var list;
    var settings = {
        theme: 'lemon',
        sticky: false, // всегда отображать
        life: 10000,
        horizontalEdge: 'bottom',
        verticalEdge: 'right',
        heading: ''
    };

    return {
        init: function (url) {
            var _this = this;
            $.notific8('zindex', 11500);
            _this.show();
            setInterval(function(){ _this.show(url);}, 5000);

        },
        show: function(url) {
            if (url != undefined) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response != '') {
                            settings = $.extend(settings, response.settings);
                            $.notific8(response.text, settings);
                        }
                    }
                });
            }
        }
    };
    

}();


