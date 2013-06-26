;(function($) {

    var methods = {
        init: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmDatePicker');
                if (data) return;
                // Initialize
                var year = $this.find('select[name$="[year]"]');
                data = {
                    target:     $this,
                    day:        $this.find('select[name$="[day]"]'),
                    month:      $this.find('select[name$="[month]"]'),
                    year:       year,
                    picker:     $('<input type="text" class="sfWidgetFormDmDatePicker '+ year.attr('class') +' " />').datepicker({
                        minDate: new Date((($('option:first', year).next().val()) ? $('option:first', year).next().val() : $('option:first', year).val()), 0, 1),
                        maxDate: new Date($('option:last', year).val(), 11, 31),
                        numberOfMonths: parseInt($this.attr('data-ui-number-of-months')),
                        showButtonPanel: ($this.attr('data-ui-show-button-panel') == 'true') ? true : false,
                        changeMonth: ($this.attr('data-ui-change-month') == 'true') ? true : false,
                        changeYear: ($this.attr('data-ui-change-year') == 'true') ? true : false,
                        constrainInput: ($this.attr('data-ui-constrain-input') == 'true') ? true : false,
                        dateFormat: ($.datepicker.regional[$this.attr('data-ui-date-format')]) ? $.datepicker.regional[$this.attr('data-ui-date-format')].dateFormat : $this.attr('data-ui-date-format'),
                        onClose: function() {
                            methods['serialize'].apply($this,[]);
                        },
                        beforeShow: function() {
                            if ($('#ui-datepicker-div').parent().attr('id') != 'sf_widget_form_dm_date_picker_wrapper') {
                                $('#ui-datepicker-div').wrap('<div id="sf_widget_form_dm_date_picker_wrapper" />');
                            };
                            $('#sf_widget_form_dm_date_picker_wrapper').removeClass().addClass($this.attr('data-ui-wrapper-class'));
                        }
                    }),
                    pick:      ($this.attr('data-ui-button-open') != '') ? $($this.attr('data-ui-button-open')) : null,
                    clear:     ($this.attr('data-ui-button-clear') != '') ? $($this.attr('data-ui-button-clear')) : null,
                    original:  null
                };

                // Reorder DOM
                data.original = $this.html();
                $this.empty();
                $this.append(data.picker)

                if (data.pick) {
                    $this.append(data.pick);
                    data.pick.click(function(){
                        data.picker.datepicker('show');
                    });
                };

                if (data.clear) {
                    $this.append(data.clear);
                    data.clear.click(function(){
                        data.picker.datepicker('setDate', null);
                        methods.serialize.apply($this,[]);
                    });
                };

                var $hidden = $('<span style="display:none;"></span>').append(data.day).append(data.month).append(data.year);
                $this.append($hidden);

                // Save settings
                $.data(this, 'sfWidgetFormDmDatePicker', data);

                // Load default data
                methods['deserialize'].apply($this,[]);

                // Fix nasty bug in jQuery date picker...
                $('.ui-datepicker').css('display', 'none');
            });

            return this;
        },
        deserialize: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmDatePicker');
                if (!data) {
                    methods['init'].apply($this,[]);
                    data = $.data(this, 'sfWidgetFormDmDatePicker');
                };

                if (data.year.val() == '') return;
                var currentDate =  new Date(data.year.val(), data.month.val()-1, data.day.val());
                data.picker.datepicker('setDate', currentDate).datepicker('option', 'defaultDate', currentDate);
            })
            return this;
        },
        serialize: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmDatePicker');
                if (!data) {
                    methods['init'].apply($this,[]);
                    data = $.data(this, 'sfWidgetFormDmDatePicker');
                };

                try {
                    if (data.picker.datepicker('getDate')) {
                        var date =  $.datepicker.parseDate(data.picker.datepicker('option','dateFormat'), data.picker.val());
                        data.day.val(date.getDate());
                        data.month.val(date.getMonth()+1);
                        data.year.val(date.getFullYear());
                        data.picker.datepicker('setDate', date);
                    } else {
                        throw new Error;
                    };
                } catch(e) {
                    data.day.val('');
                    data.month.val('');
                    data.year.val('');
                    data.picker.val('');
                    data.picker.datepicker('setDate', null);
                };
            });
            return this;
        },
        destroy: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmDatePicker');
                if (!data) return;
                $this.empty();
                $this.append(data.original);
                $this.removeData('sfWidgetFormDmDatePicker');
            });
            return this;
        }
    };
    $.fn.sfWidgetFormDmDatePicker = function(method) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.sfWidgetFormDmDatePicker' );
        };   
    };
     
})(jQuery);

(function($) {

    // We have to wait for regional settings to be available in order to init controll...
    function initCtrl($context) {
        var $this = $context;
        var init = function() {
            if ($.datepicker.regional[dm_configuration.culture]) {
                $this.sfWidgetFormDmDatePicker();
            } else {
                setTimeout(init, 50);
            };
        };
        init();
    };

    if ($('#dm_admin_content').length >0) {
        $.each($('#dm_admin_content').find('.sfWidgetFormDmDatePicker'), function(){
            initCtrl($(this));
        });
    };

    $('#dm_page div.dm_widget').bind('dmWidgetLaunch', function() {
        $.each($(this).find('.sfWidgetFormDmDatePicker'), function(){
            initCtrl($(this));
        });
    });

    $('div.dm.dm_widget_edit_dialog_wrap').live('dmAjaxResponse', function() {
        $.each($(this).find('.sfWidgetFormDmDatePicker'), function(){
            initCtrl($(this));
        });       
    });

})(jQuery);