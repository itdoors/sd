var ITDoorsInvoice = (function() {
    var _this = this;
    _this.init = (function() {
        $('.nav.nav-tabs>li>a').live('click',  ITDoorsAjax.updateTab(this));
    })
});
$(document).ready(function() {
    var invoice = new ITDoorsInvoice();
    invoice.init();
})