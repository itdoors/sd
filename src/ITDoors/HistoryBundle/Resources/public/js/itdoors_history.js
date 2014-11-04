var ITDoorsHistory = (function() {
    
    var defaults = {
    };
    
    function ITDoorsHistory() {
        this.params = {};
    };
    
    ITDoorsHistory.prototype.init = function(options)
    {
        this.params = $.extend(defaults, options);
    };
    
     ITDoorsHistory.prototype.initAjaxTableBtn = function($selector) {

        var params = $selector.data('params');
        var $target = $(params.target);

        $selector.die('click');
        $selector.live('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: params.url,
                data: {
                    params: JSON.stringify(params)
                },
                beforeSend: function() {
                    if (!$target.html()) {
                        $target.show();
                        $target.html(params.loadingText);
                    }
                    $target.show();
                    ITDoorsAjax.blockUI($target);
                },
                success: function(response) {
                    $target.show();
                    $target.html(response.content);
                    ITDoorsAjax.unblockUI($target);
                }
            });
        });
    };
    return new ITDoorsHistory();
})();


