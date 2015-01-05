var SD = (function() {

    var defaults = {
        ajaxFormClass: 'ajax-form',
        ajaxDeleteClass: 'ajax-delete',
        ajaxFormEntityClass: 'ajax-form-entity',
        ajaxFilterFormClass: 'ajax-filter-form',
        ajaxFormCancelBtnClass: 'sd-cancel-btn',
        ajaxMoreInfoClass: 'more-info',
        canBeResetedClass: 'can-be-reseted',
        select2Class: 'sd-select2',
        daterangeClass: 'sd-daterange',
        daterangeTextClass: 'sd-daterange-text',
        daterangeStartClass: 'sd-daterange-start',
        daterangeEndClass: 'sd-daterange-end',
        ajaxFormUrl: '',
        ajaxDeleteUrl: '',
        assetsDir: '',
        loadingImgPath: 'templates/metronic/img/ajax-loading.gif'
    };

    function SD(){
        this.params = {};
    };

    SD.prototype.init = function(options)
    {
        this.params = $.extend(defaults, options);

        this.params.loadingImgPath = this.params.assetsDir + 'templates/metronic/img/ajax-loading.gif';

        this.initAjaxForm();

        this.initAjaxDelete();

        this.initMoreInfo();
    }

    SD.prototype.initAjaxForm = function()
    {
        var selfSD = this;

        var target;

        $('.' + selfSD.params.ajaxFormClass).die('click');
        $('.' + selfSD.params.ajaxFormClass).live('click', function(e){
            e.preventDefault();

            var selfAjaxFormClassObject = $(this);

            var targetId = $(this).data('target_holder');
            var formName = $(this).data('form_name');
            var defaultData = $(this).data('default');
            var postFunction = $(this).data('post_function');
            var postTargetId = $(this).data('post_target_id');
            var model = $(this).data('model');
            var modelId = $(this).data('model-id');

            if (!formName)
            {
                alert('Form name must be set');

                return null;
            }

            if (!targetId)
            {
                targetId = 'targerform' + selfSD.random();

                target = $('<div></div>').attr('id', targetId).css('display', 'none');

                target.insertAfter($(this));
            }
            else
            {
                target = $('#' + targetId);
            }

            target.css('display', 'block');

            target.html(target.data('text'));

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: selfSD.params.ajaxFormUrl,
                data: {
                    formName: formName,
                    defaultData: defaultData,
                    targetId: targetId,
                    postFunction: postFunction,
                    postTargetId: postTargetId,
                    model: model,
                    modelId: modelId
                },
                beforeSend: function ()
                {
                    selfSD.blockUI(selfAjaxFormClassObject);
                },
                success: function(response)
                {
                    selfSD.unblockUI(selfAjaxFormClassObject);

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

        $('.' + selfSD.params.ajaxFormEntityClass).die('submit');
        $('.' + selfSD.params.ajaxFormEntityClass).live('submit', function(e){

            e.preventDefault();

            var self = $(this);

            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {

                    selfSD.blockUI(self);
                },
                success: function(response) {

                    var target = $('#' + response.targetId);

                    selfSD.unblockUI(self);

                    if (response.error)
                    {
                        target.html(response.html);

                        return;
                    }
                    if (response.success)
                    {
                        target.html('');

                        var dialogCloseBtn = $('div.modal button.close');

                        if (dialogCloseBtn.length)
                        {
                            dialogCloseBtn.trigger('click');
                        }

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

        SD.prototype.initMoreInfo = function()
        {
            var selfSD = this;

            $('.' + selfSD.params.ajaxMoreInfoClass).die('click');
            $('.' + selfSD.params.ajaxMoreInfoClass).live('click', function(e){
                e.preventDefault();

                var selfAjaxMoreInfoObject = $(this);

                var targetId = $(this).data('target_holder');

                var target = $('#' + targetId);

                var params = $(this).data('params');
                var urlMoreInfo = $(this).data('url-more-info');

                target.css('display', 'block');

                target.html(target.data('text'));

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: urlMoreInfo,
                    data: params,
                    beforeSend: function ()
                    {
                        selfSD.blockUI(selfAjaxMoreInfoObject);
                    },
                    success: function(response) {
                        //console.log(response);
                        selfSD.unblockUI(selfAjaxMoreInfoObject);
                        if (response.success)
                        {
                            target.html(response.html);
                        }
                    },
                    error: function () {
                        selfSD.unblockUI(selfAjaxMoreInfoObject);
                    }
                })
            })
        };

        $('.' + selfSD.params.ajaxFormCancelBtnClass).die('click');
        $('.' + selfSD.params.ajaxFormCancelBtnClass).live('click', function(e){

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
        var selfSD = this;

        var target = $('#' + targetId);

        var url = target.data('url');
        var params = target.data('params');

        $.ajax({
            type: 'POST',
            url: url,
            data: params,
            beforeSend: function () {
                selfSD.blockUI(target);
            },
            success: function (response) {
                target.html(response);
                selfSD.unblockUI(target);
            }
        });
    }

    SD.prototype.initAjaxDelete = function()
    {
        var selfSD = this;

        $('.' + this.params.ajaxDeleteClass).die('click');
        $('.' + this.params.ajaxDeleteClass).live('click', function(e) {
            e.preventDefault();

            var parentElement = $(this).parents('tr');
            var params = $(this).data('params');
            var question = $(this).data('question');
            var url = $(this).data('url');
            
            if (question === '' || question === undefined) {
                question = 'Are you sure?';
            }
            if (url === '' || url === undefined) {
                url = selfSD.params.ajaxDeleteUrl;
            }
            if (!confirm(question))
            {
                return;
            }

            parentElement.css('opacity', '0.5');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: "json",
                data: {
                    params: params
                },
                success: function (response)
                {
                    if (response.error) {
                        alert(response.error);
                        parentElement.css('opacity', '1.0');
                    } else {
                        parentElement.remove();
                    }
                }
            });
        });
    };

    SD.prototype.blockUI = function (el, centerY) {
        var selfSD = this;

        if (el.height() <= 400) {
            centerY = true;
        }
        var urlImg = selfSD.params.loadingImgPath == undefined ? 'templates/metronic/img/ajax-loading.gif' : selfSD.params.loadingImgPath;
        el.block({
            message: '<img src="' + urlImg + '" align="">',
            centerY: centerY != undefined ? centerY : true,
            css: {
                top: '10%',
                border: 'none',
                padding: '2px',
                backgroundColor: 'none'
            },
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 0.5,
                cursor: 'wait'
            }
        });
    };

    // wrapper function to  un-block element(finish loading)
    SD.prototype.unblockUI = function (el, clean) {
       // el.css('position', '');
        el.css('zoom', '');
        el.unblock();
    };

    SD.prototype.select2 = function (selector, defaultParams){

        var $selector = $(selector);

        if (!defaultParams)
        {
            defaultParams = {};
        }

        if (!$selector.length)
        {
            return false;
        }

        if (!$.isFunction($.fn.select2))
        {
            return false;
        }

        var url = $selector.data('url');
        var urlById = $selector.data('url-by-id');

        var selectorParams = $selector.data('params');

        var params = $.extend({
            minimumInputLength: 2,
            allowClear: true
        }, selectorParams);

        params = $.extend(params, defaultParams);

        if (url)
        {
            params.ajax = {
                url: url,
                dataType: 'json',
                data: function (term, page) {
                    return {
                        query: term,
                        q: term
                    };
                },
                results: function (data, page) {
                    return {
                        results: data
                    };
                }
            }
        }

        if (urlById)
        {
            params.initSelection = function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax(urlById, {
                        data: {
                            id: id
                        },
                        dataType: "json"
                    }).done(function (data) {
                            callback(data)
                        });
                }
            }
        }

        $selector.select2(params);
    }

    SD.prototype.addGetParam = function (key, value) {
        key = escape(key); value = escape(value);

        var kvp = document.location.search.substr(1).split('&');
        if (kvp == '') {
            //location.href = '?' + key + '=' + value;
            window.history.pushState({}, "Title", '?' + key + '=' + value);

        }
        else {

            var i = kvp.length; var x; while (i--) {
                x = kvp[i].split('=');

                if (x[0] == key) {
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }

            if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

            //this will reload the page, it's likely better to store this until finished
            window.history.pushState({}, "Title", '?'+kvp.join('&'));
            //location.href = kvp.join('&');
        }
    }

    SD.prototype.updateCalendar = function(targetId)
    {
        var selfSD = this;

        var target = $(targetId);

        $(targetId).fullCalendar('refetchEvents');
    }


    return new SD();
})();