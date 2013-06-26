dmDateTimePicker for Diem Extended
===============================

Author: [TheCelavi](http://www.runopencode.com/about/thecelavi)
Version: 1.0
Stability: Stable  
Date: June 24th, 2013
Courtesy of [Run Open Code](http://www.runopencode.com)   
License: [Free for all](http://www.runopencode.com/terms-and-conditions/free-for-all)

dmDateTimePicker for Diem Extended is datepicker, timepicker and datetimepicker control for Diem Extended (probably it
will work for base Diem).

It is integration of sfWidgetFormDate, sfWidgetFormTime and sfWidgetFormDateTime widgets with jQuery datepicker control
with help of timepicker add on (see [https://github.com/trentrichardson/jQuery-Timepicker-Addon](https://github.com/trentrichardson/jQuery-Timepicker-Addon)). Three new widgets are available:

- sfWidgetFormDmDatePicker
- sfWidgetFormDmTimePicker
- sfWidgetFormDmDateTimePicker

It can be used anywhere - front, admin and admin front as well.

If you are using it in admin, in your `schema.yml`, for your column add `extra: datepicker` or `extra: timepicker` or
`extra: datetimepicker` - whatever you need.


Options:
---------------------

All options are inherited via sfWidgetFormDate, sfWidgetFormTime and sfWidgetFormDateTime, while ui options are added:

### Date picker:

- `ui_date_format`:       Date formats, see: http://api.jqueryui.com/datepicker/#utility-formatDate, default is date format for current culture
- `ui_number_of_months`:  Number of months to display, default is 1
- `ui_show_button_panel`: Whether to show buttons Today and Done, default is true
- `ui_change_month`:      Whether to show month drop-down, default is true
- `ui_change_year`:       Whether to show year drop-down, default is true
- `ui_constrain_input`:   Enable only to input characters defined in date format, default is true

### Time picker:

- `ui_time_format`:         Time formats, see: http://trentrichardson.com/examples/timepicker/#tp-formatting
- `ui_control_type`:        How to display time control, using 'slider' or 'select', default is slider
- `ui_show_button_panel`:   Whether to display buttons Now and Done - this is very useful, default is true
- `ui_step_hour`:           Steps for hours in slider control, default is 1
- `ui_step_minute`:         Steps for minutes in slider control, default is 1
- `ui_step_second`:         Steps for seconds in slider control, default is 1
- `ui_hour_grid`:           Grid for slider for hours, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.
- `ui_minute_grid`:         Grid for slider for minutes, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.
- `ui_second_grid`:         Grid for slider for seconds, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.

### Datetime picker:

- All stated above, Datetime picker is combination of previous two.

### Options for all three stated

- `theme`:  			  Which CSS styles to use from themes in config, or default theme will be used set in config. You can set this to null in order not to load any CSS. This will also load any default configuration, if you add any.
- `ui_button_open`:       HTML code for open picker button, if you want to have this button
- `ui_button_clear`:      HTML code for clear value button, if you want to have this button


Themes support:
---------------------

It is important to have same pickers on whole site. Therefore, they are defined in config.yml:


	default:
	  dmDateTimePickerPlugin:
	    default_theme: admin
	    themes:
	      admin:
	        defaults:
	          ui_button_open: ~
	          ui_button_clear: ~
	          ui_show_button_panel: ~
	####  IMPORTANT NOTES ####
	# All other settings for #
	# datetime picker are    #
	# taken from respective  #
	# date and time picker   #
	# theme settings         #
	##########################


	  dmDatePickerPlugin:
	    default_theme: admin
	    themes:
	      admin:
	        wrapper_class: dm
	        css_files:
	          - lib.ui
	          - lib.ui-datepicker
	          - dmDateTimePickerPlugin.basic
	        defaults:
	          ui_date_format:
	            default: ~
	            en: ~
	          ui_number_of_months: ~
	          ui_show_button_panel: ~
	          ui_change_month: ~
	          ui_change_year: ~
	          ui_constrain_input: ~
	          ui_button_open: ~
	          ui_button_clear: ~

	  dmTimePickerPlugin:
	    default_theme: admin
	    themes:
	      admin:
	        wrapper_class: dm
	        css_files:
	          - lib.ui
	          - lib.ui-datepicker
	          - lib.ui-slider
	          - dmDateTimePickerPlugin.basic
	          - dmDateTimePickerPlugin.tp-add-on
	        defaults:
	          ui_time_format:
	            default: ~
	            en: ~
	          ui_control_type: ~
	          ui_show_button_panel: ~
	          ui_step_hour: ~
	          ui_step_minute: ~
	          ui_step_second: ~
	          ui_hour_grid: ~
	          ui_minute_grid: ~
	          ui_second_grid: ~
	          ui_button_open: ~
	          ui_button_clear: ~
			  
			  
This is default configuration, you can have them as many as you like. With wrapper class defined, you can have
as many styles of pickers as you like, different on same page.

For each theme, you can set which CSS files to load, how to wrap control and target it with that CSS - or you can set
to null `~` and to load CSS with page manually. 

For each theme you can set default configuration - in that matter, you can achieve same look and feel for all pickers.

Internationalization:
---------------------			  

It is fully supported now, appropriate translation files are loaded with the each picker. Translations are in Javascript
files.

IMPORTANT NOTE: It is required to have for each culture appropriate Javascript file with translation of datepicker and timepicker in 
order for this control to work.

For each culture, default format of time and date will be used - you can override this with config.yml or on initialization of control.

Config.yml:
---------------------			  

Have in mind that it is not advised to modify original config.yml - use it as starting point, but your config ought to be in `root/config/dm/config.yml`.





















