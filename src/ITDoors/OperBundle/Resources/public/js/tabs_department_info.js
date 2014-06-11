$(document).ready(function() {
    function ajaxTabs() {
        var block_id = $(this).data('block');
        $.ajax({
            type: 'POST',
            url: $(this).attr('data-url'),
            beforeSend: function() {
                ITDoorsAjax.blockUI($('#'+block_id));
            },
            success: function(response) {
                $('#'+block_id).empty().append(response);
                ITDoorsAjax.unblockUI($('#'+block_id));
            }
        });
    }
    $('#ajax_tabs>li>a').on('click', ajaxTabs);
})