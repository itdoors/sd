var ITDoorsInvoice = (function() {
    var _this = this;
    _this.changeBlockAjax = (function() {
        var obj = this;
        $.ajax({
            type: 'POST',
            url: $(obj).attr('data-url'),
            beforeSend: function() {
                ITDoorsAjax.blockUI($('#block-ajax'));
            },
            success: function(response) {
                $('#block-ajax').empty().append(response);
                ITDoorsAjax.unblockUI($('#block-ajax'));
            }
        });
    }),
    _this.init = (function() {
        $('.nav.nav-tabs>li>a').on('click', _this.changeBlockAjax);
    })
});
$(document).ready(function() {
    var invoice = new ITDoorsInvoice();
    invoice.init();
})