var ITDoorsAjax = (function() {

    var defaults = {
        ajaxFormClass: 'itdoors-ajax-form',
        ajaxFormBtnClass: 'itdoors-ajax-form-btn',
        ajaxFilterFormClass: 'ajax-filter-form',
        shortFormClass: 'itdoors-short-form',
        canBeResetedClass: 'can-be-reseted',
        select2Class: 'itdoors-select2',
        daterangeClass: 'itdoors-daterange',
        daterangeCustomClass: 'itdoors-daterange-custom',
        daterangeTextClass: 'itdoors-daterange-text',
        daterangeStartClass: 'itdoors-daterange-start',
        daterangeEndClass: 'itdoors-daterange-end',
        assetsDir: '',
        loadingImgPath: ''
    };

    function ITDoorsAjax(){
        this.params = {};
    };

    ITDoorsAjax.prototype.init = function(options)
    {
        this.params = $.extend(defaults, options);

        this.params.loadingImgPath = this.params.assetsDir + 'templates/metronic/img/ajax-loading.gif';

        this.initAjaxFilterForm();

        this.initAjaxForm();

        this.initSelect2();

        this.initDateRange();

        this.initDateRangeCustom();
    }

    ITDoorsAjax.prototype.initSelect2 = function()
    {
        if (!jQuery().select2) {
            return;
        }

        var selfClass = this;

        $('.' + selfClass.params.select2Class).each(function(index) {
            selfClass.select2($(this));
        });
    }

    ITDoorsAjax.prototype.initDateRange = function()
    {
        var selfClass = this;

        if (!jQuery().daterangepicker) {
            return;
        }

        $('.' + selfClass.params.daterangeClass).each(function(index) {
            var self = $(this);

            var $form = self.closest('form');

            var btn =
                '<span class="input-group-btn">' +
                    '<button class="btn default date-range-toggle" type="button">' +
                    '   <i class="fa fa-calendar"></i>' +
                    '</button>'
            '</span>';

            self.append($(btn));

            $(this).daterangepicker({
                    opens: (App.isRTL() ? 'left' : 'right'),
                    format: 'DD.MM.YYYY',
                    separator: ' to ',
                    startDate: moment().subtract('days', 29),
                    endDate: moment()
                },
                function (start, end) {
                    var daterangeStart = self.parent().find('.' + selfClass.params.daterangeStartClass);
                    var daterangeEnd = self.parent().find('.' + selfClass.params.daterangeEndClass);

                    daterangeStart.val(start.format('DD.MM.YYYY'));
                    daterangeEnd.val(end.format('DD.MM.YYYY'));

                    self.find('input').val(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));

                    if ($form.hasClass(selfClass.params.shortFormClass))
                    {
                        $form.trigger('change');
                    }
                }
            );
        });
    }

    ITDoorsAjax.prototype.initDateRangeCustom = function()
    {
        var selfClass = this;

        if (!jQuery().daterangepicker) {
            return;
        }

        $('.' + selfClass.params.daterangeCustomClass).each(function(index) {
            var self = $(this);

            var $form = self.closest('form');

            var btn =
                '<span class="input-group-btn">' +
                    '<button class="btn default date-range-toggle" type="button">' +
                    '   <i class="fa fa-calendar"></i>' +
                    '</button>'
                '</span>';

            self.append($(btn));

            $(this).daterangepicker({
                    opens:  'right',
                    startDate: moment().subtract('days', 29),
                    endDate: moment(),
                    dateLimit: {
                        days: 60
                    },
                    showDropdowns: false,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                        'Сегодня': [moment(), moment()],
                        'Вчера': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Последняя неделя': [moment().subtract('days', 6), moment()],
                        'Последние 30 дней': [moment().subtract('days', 29), moment()],
                        'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                        'Предыдущий месяц': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    },
                    buttonClasses: ['btn'],
                    applyClass: 'blue',
                    cancelClass: 'default',
                    format: 'MM/DD/YYYY',
                    separator: ' до ',
                    language: 'ru',
                    locale: {
                        applyLabel: 'Принять',
                        fromLabel: 'До',
                        toLabel: 'С',
                        customRangeLabel: 'Выберите интервал',
                        daysOfWeek: ["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],
                        monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
                        firstDay: 1
                    }
                },
                function (start, end) {
                    var daterangeStart = self.parent().find('.' + selfClass.params.daterangeStartClass);
                    var daterangeEnd = self.parent().find('.' + selfClass.params.daterangeEndClass);

                    daterangeStart.val(start.format('DD.MM.YYYY'));
                    daterangeEnd.val(end.format('DD.MM.YYYY'));

                    self.find('input').val(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));

                    if ($form.hasClass(selfClass.params.shortFormClass))
                    {
                        $form.trigger('change');
                    }
                }
            );

            self.find('input').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $(this).show();
        });
    }

    ITDoorsAjax.prototype.updateList = function(targetId)
    {
        var selfClass = this;

        var target = $('#' + targetId);

        var url = target.data('url');
        var params = target.data('params');

        $.ajax({
            type: 'POST',
            url: url,
            data: params,
            beforeSend: function () {
                selfClass.blockUI(target);
            },
            success: function (response) {
                target.html(response);
                selfClass.unblockUI(target);
            }
        });
    }

    ITDoorsAjax.prototype.blockUI = function (el, centerY) {
        var selfClass = this;

        if (el.height() <= 400) {
            centerY = true;
        }
        el.block({
            message: '<img src="' + selfClass.params.loadingImgPath + '" align="">',
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
    ITDoorsAjax.prototype.unblockUI = function (el, clean) {
        el.css('position', '');
        el.css('zoom', '');
        el.unblock();
    };

    ITDoorsAjax.prototype.select2 = function (selector, defaultParams){

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

        var choices = $selector.data('choices');

        if (choices) {
            params.data = choices;
        }

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

    ITDoorsAjax.prototype.resetForm = function(form)
    {
        var selfClass = this;

        form.find('.' + selfClass.params.canBeResetedClass).each(function(index){
            if ($(this).hasClass(selfClass.params.select2Class))
            {
                $(this).select2('data', null);
            }

            if ($(this).hasClass(selfClass.params.daterangeStartClass) ||
                $(this).hasClass(selfClass.params.daterangeEndClass) ||
                $(this).hasClass(selfClass.params.daterangeTextClass))
            {
                $(this).val('');
            }

            if ($(this).attr('type') == 'checkbox')
            {
                if ($(this).is(':checked'))
                {
                    $(this).trigger('click');
                }
            }
        });
    }

    ITDoorsAjax.prototype.initAjaxFilterForm = function()
    {
        var selfClass = this;

        var $form = $('.' + selfClass.params.ajaxFilterFormClass)

        $('.' + selfClass.params.ajaxFilterFormClass + ' .itdoors-filter-cancel-btn').live('click', function(e){
            e.preventDefault();

            var resetField = $form.find('.ajax-form-reset-field');

            selfClass.resetForm($form);

            resetField.val(1);

            $form.submit();

            resetField.val(0);
        });

        $form.live('submit', function(e){

            e.preventDefault();

            var self = $(this);

            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {

                    selfClass.blockUI(self);
                },
                success: function(response) {

                    selfClass.unblockUI(self);

                    if (response.error)
                    {
                        return;
                    }

                    if (response.success)
                    {
                        if (response.successFunctions){
                            var successFunctions = JSON.parse(response.successFunctions);

                            for (key in successFunctions) {
                                var successFunction = successFunctions[key].split('.');

                                if (successFunction[0] && successFunction[1]) {
                                    if (window[successFunction[0]] && typeof window[successFunction[0]][successFunction[1]] === 'function'){
                                        formok = window[successFunction[0]][successFunction[1]](key);
                                    }
                                }
                                else {
                                    if (typeof window[successFunctions[key]] === 'function'){
                                        formok = window[successFunctions[key]](key);
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });

        var $formShort = $('.' + selfClass.params.ajaxFilterFormClass + '.' + selfClass.params.shortFormClass).live('change', function(){
            $(this).submit();
        });
    };

    /**
     * Init Btn, onClick - load form from params
     *
     * @param {Object} $selector
     */
    ITDoorsAjax.prototype.initAjaxFormBtn = function($selector) {
        var selfClass = this;

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
                    console.log('before send');
                    if (!$target.html()) {
                        $target.show();
                        $target.html(params.loadingText);
                    }
                    $target.show();
                    selfClass.blockUI($target);
                },
                success: function(response) {
                    $selector.hide();
                    $target.show();
                    $target.html(response.content);
                    selfClass.unblockUI($target);
                }
            });
        });
    };

    ITDoorsAjax.prototype.initAjaxForm = function()
    {
        var selfClass = this;

        var $form = $('.' + selfClass.params.ajaxFormClass)

        $('.' + selfClass.params.ajaxFormClass + ' .itdoors-form-cancel-btn').live('click', function(e){
            e.preventDefault();

            var params = JSON.parse($(this).closest('form').find('.params-hidden').val());

            var $selector = $(params.selector);
            var $target = $(params.target);

            $selector.fadeIn();
            $target.html('');
        });

        $form.live('submit', function(e){

            e.preventDefault();

            var self = $(this);

            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {

                    selfClass.blockUI(self);
                },
                success: function(response) {

                    selfClass.unblockUI(self);

                    if (response.error)
                    {
                        return;
                    }

                    if (response.success)
                    {
                        var params = JSON.parse(response.params);

                        var $selector = $(params.selector);
                        var $target = $(params.target);

                        $selector.fadeIn();
                        $target.html('');

                        if (params.successFunctions){
                            var successFunctions = params.successFunctions;

                            for (key in successFunctions) {
                                var successFunction = successFunctions[key].split('.');

                                if (successFunction[0] && successFunction[1]) {
                                    if (window[successFunction[0]] && typeof window[successFunction[0]][successFunction[1]] === 'function'){
                                        formok = window[successFunction[0]][successFunction[1]](key);
                                    }
                                }
                                else {
                                    if (typeof window[successFunctions[key]] === 'function'){
                                        formok = window[successFunctions[key]](key);
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    }

    return new ITDoorsAjax();
})();