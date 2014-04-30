var ITDoorsInvoice = (function() {
    var _this = this;
    _this.changeBlockAjax = (function(){
        $.post( $(this).attr('data-url'), function(data){
            $('#block-ajax').empty().append(data);
        });
    }),
    _this.init = (function(){
        $('.nav.nav-tabs>li>a').on('click', _this.changeBlockAjax);
    })
});
$(document).ready(function(){
    var invoice = new ITDoorsInvoice();
    invoice.init();
})