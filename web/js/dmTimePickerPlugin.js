;(function($) {
    var methods = {
        init: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmTimePicker');
                if (data) return;

                // Initialize
                var hour = $this.find('select[name$="[hour]"]'),
                    minute = $this.find('select[name$="[minute]"]'),
                    second = ($this.find('select[name$="[second]"]').length == 0) ? null : $this.find('select[name$="[second]"]');
                data = {
                    target:     $this,
                    hour:       hour,
                    minute:     minute,
                    second:     second,
                    picker:     $('<input type="text" class="sfWidgetFormDmTimePicker '+ hour.attr('class') +'" />').timepicker({
                        showSecond: (second != null) ? true : false,
                        controlType: $this.attr('data-ui-control-type'),
                        showButtonPanel: ($this.attr('data-ui-show-button-panel') == 'true') ? true : false,
                        stepHour: parseInt($this.attr('data-ui-step-hour')),
                        stepMinute: parseInt($this.attr('data-ui-step-minute')),
                        stepSecond: parseInt($this.attr('data-ui-step-second')),
                        hourGrid: parseInt($this.attr('data-ui-hour-grid')),
                        minuteGrid: parseInt($this.attr('data-ui-minute-grid')),
                        secondGrid: parseInt($this.attr('data-ui-second-grid')),
                        hourMin: parseInt((($('option:first', hour).next().val()) ? $('option:first', hour).next().val() : $('option:first', hour).val())),
                        hourMax: parseInt($('option:last', hour).val()),
                        minuteMin: parseInt((($('option:first', minute).next().val()) ? $('option:first', minute).next().val() : $('option:first', minute).val())),
                        minuteMax: parseInt($('option:last', minute).val()),
                        secondMin: (second) ? parseInt((($('option:first', second).next().val()) ? $('option:first', second).next().val() : $('option:first', second).val())) : 0,
                        secondMax: (second) ? parseInt($('option:last', second).val()) : 59,
                        timeFormat: ($.timepicker.regional[$this.attr('data-ui-time-format')]) ? $.timepicker.regional[$this.attr('data-ui-time-format')].timeFormat : $this.attr('data-ui-time-format'),
                        onClose: function(dateText, instance) {
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
                $.data(this, 'sfWidgetFormDmTimePicker', data);

                // Load default data
                methods['deserialize'].apply($this,[]);

                // Fix nasty bug in jQuery date picker...
                $('.ui-datepicker').css('display', 'none');

            });
            return this;
        },
        deserialize: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmTimePicker');
                if (!data) {
                    methods['init'].apply($this,[]);
                    data = $.data(this, 'sfWidgetFormDmTimePicker');
                };

                if (data.hour.val() == '') return;
                var date = new Date();
                date.setHours(data.hour.val(), data.minute.val(), ((data.second == null) ? 0 : data.second.val()));
                data.picker.datepicker('setDate', date);
            });

            return this;
        },
        serialize: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmTimePicker');
                if (!data) {
                    methods['init'].apply($this,[]);
                    data = $.data(this, 'sfWidgetFormDmTimePicker');
                };

                try {
                    if (data.picker.datepicker('getDate')) {
                        var time =  $.datepicker.parseTime(data.picker.datepicker('option','timeFormat'), data.picker.val());
                        var date = new Date();
                        date.setHours(time.hour);
                        date.setMinutes(time.minute);
                        date.setSeconds(time.second);
                        data.hour.val(date.getHours());
                        data.minute.val(date.getMinutes());
                        if (data.second != null) {
                            data.second.val(date.getSeconds());
                        };
                        data.picker.datepicker('setDate', date);
                    } else {
                        throw new Error;
                    };
                } catch(e) {
                    data.hour.val('');
                    data.minute.val('');
                    if (data.second != null){
                        data.second.val('');
                    }
                    data.picker.val('');
                    //data.picker.datepicker('setDate', null); - Timepicker cannot set date to null
                };
            });
            return this;
        },
        destroy: function() {
            this.each(function(){
                var $this = $(this), data = $.data(this, 'sfWidgetFormDmTimePicker');
                if (!data) return;
                $this.empty();
                $this.append(data.original);
                $this.removeData('sfWidgetFormDmTimePicker');
            });

        }
    };
    $.fn.sfWidgetFormDmTimePicker = function(method) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.sfWidgetFormDmTimePicker' );
        };  
    };
     
})(jQuery);

(function($) {

    // We have to wait for regional settings to be available in order to init controll...
    function initCtrl($context) {
        var $this = $context;
        var init = function() {
            if ($.timepicker.regional[dm_configuration.culture]) {
                $this.sfWidgetFormDmTimePicker();
            } else {
                setTimeout(init, 50);
            };
        };
        init();
    };

    if ($('#dm_admin_content').length >0) {
        $.each($('#dm_admin_content').find('.sfWidgetFormDmTimePicker'), function(){
            initCtrl($(this));
        });
    };

    $('#dm_page div.dm_widget').bind('dmWidgetLaunch', function() {
        $.each($(this).find('.sfWidgetFormDmTimePicker'), function(){
            initCtrl($(this));
        });
    });

    $('div.dm.dm_widget_edit_dialog_wrap').live('dmAjaxResponse', function() {
        $.each($(this).find('.sfWidgetFormDmTimePicker'), function(){
            initCtrl($(this));
        });
    });

})(jQuery);