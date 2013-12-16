var SD = (function() {

    var defaults = {
        ajaxFormClass: 'ajax-form',
        ajaxDeleteClass: 'ajax-delete',
        ajaxFormEntityClass: 'ajax-form-entity',
        ajaxFormCancelBtnClass: 'sd-cancel-btn',
        ajaxFormUrl: '',
        ajaxDeleteUrl: ''
    };

    function SD(){
        this.params = {};
    };

    SD.prototype.init = function(options)
    {
        this.params = $.extend(defaults, options);

        this.initAjaxForm();

        this.initAjaxDelete();
    }

    SD.prototype.initAjaxForm = function()
    {
        var self = this;

        var target;

        $('.' + self.params.ajaxFormClass).live('click', function(e){
            e.preventDefault();

            var selfAjaxFormClassObject = $(this);

            var targetId = $(this).data('target_holder');
            var formName = $(this).data('form_name');
            var defaultData = $(this).data('default');
            var postFunction = $(this).data('post_function');
            var postTargetId = $(this).data('post_target_id');

            if (!formName)
            {
                alert('Form name must be set');

                return null;
            }

            if (!targetId)
            {
                targetId = 'targerform' + self.random();

                target = $('<div></div>').attr('id', targetId).css('display', 'none');

                target.insertAfter($(this));
            }
            else
            {
                target = $('#' + targetId);
            }

            target.css('display', 'block');

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: self.params.ajaxFormUrl,
                data: {
                    formName: formName,
                    defaultData: defaultData,
                    targetId: targetId,
                    postFunction: postFunction,
                    postTargetId: postTargetId
                },
                beforeSend: function ()
                {
                    selfAjaxFormClassObject.css('opacity', '0.5');
                },
                success: function(response) {

                    selfAjaxFormClassObject.css('opacity', '1');

                    if (response.error)
                    {
                        target.html(response.html);
                    }
                    if (response.success)
                    {
                        if (postFunction)
                        {
                            if (typeof window[postFunction] === 'function'){
                                formok = window[postFunction](postTargetId);
                            }
                        }
                    }
                }
            })
        })

        $('.' + self.params.ajaxFormEntityClass).live('submit', function(e){

            e.preventDefault();

            var self = $(this);

            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {

                    self.css('opacity', '0.5');
                },
                success: function(response) {

                    var target = $('#' + response.targetId);

                    self.css('opacity', '1');

                    if (response.error)
                    {
                        target.html(response.html);

                        return;
                    }
                    if (response.success)
                    {
                        target.html('');

                        if (response.postFunction)
                        {
                            if (typeof window["SD"][response.postFunction] === 'function'){
                                formok = window["SD"][response.postFunction](response.postTargetId);
                            }
                        }
                    }
                }
            });
        });

        $('.' + self.params.ajaxFormCancelBtnClass).live('click', function(e){

            e.preventDefault();

            var targetHolder = $(this).parents('form').find('input[name="targetId"]');

            var targetId;

            if (targetHolder.length)
            {
                targetId = targetHolder.val();
            }

            if (!targetId)
            {
                return;
            }

            var target = $('#' + targetId);

            if (target)
            {
                target.html('');
            }
        })

    };

    SD.prototype.random = function() {
        var now = new Date().getTime() /
            1000;
        var s = parseInt(now, 10);

        return (Math.round((now - s) * 1000)) +  s;
    }

    SD.prototype.updateList = function(targetId)
    {
        var target = $('#' + targetId);

        var url = target.data('url');
        var params = target.data('params');

        $.ajax({
            type: 'POST',
            url: url,
            data: params,
            beforeSend: function () {
                target.css('opacity', '0.5');
            },
            success: function (response) {
                target.html(response);
                target.css('opacity', '1');
            }
        });
    }

    SD.prototype.initAjaxDelete = function()
    {
        var selfSD = this;

        $('.' + this.params.ajaxDeleteClass).live('click', function(e) {
            e.preventDefault();

            if (!confirm('Are you sure?'))
            {
                return;
            }

            var parentElement = $(this).parents('tr');
            var params = $(this).data('params');

            parentElement.css('opacity', '0.5');


            $.ajax({
                url: selfSD.params.ajaxDeleteUrl,
                type: 'POST',
                data: {
                    params: params
                },
                success: function (response)
                {
                    parentElement.remove();
                }
            });
        })
    }

    return new SD();
})();