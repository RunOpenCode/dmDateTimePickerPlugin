<?php

/**
 * Replaces standard Symfony UI time with jQuery time picker
 *
 * @author TheCelavi
 */
class sfWidgetFormDmTimePicker extends sfWidgetFormTime {

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * format:                 The time format string (%hour%:%minute%:%second%)
     *  * format_without_seconds: The time format string without seconds (%hour%:%minute%)
     *  * with_seconds:           Whether to include a select for seconds (false by default)
     *  * hours:                  An array of hours for the hour select tag (optional)
     *  * minutes:                An array of minutes for the minute select tag (optional)
     *  * seconds:                An array of seconds for the second select tag (optional)
     *  * can_be_empty:           Whether the widget accept an empty value (true by default)
     *  * empty_values:           An array of values to use for the empty value (empty string for hours, minutes, and seconds by default)
     *
     * Available jQuery UI Time picker options:
     *
     *  * ui_time_format:         Time formats, see: http://trentrichardson.com/examples/timepicker/#tp-formatting
     *  * ui_control_type:        How to display time control, using 'slider' or 'select', default is slider
     *  * ui_show_button_panel:   Whether to display buttons Now and Done - this is very useful, default is true
     *  * ui_step_hour:           Steps for hours in slider control, default is 1
     *  * ui_step_minute:         Steps for minutes in slider control, default is 1
     *  * ui_step_second:         Steps for seconds in slider control, default is 1
     *  * ui_hour_grid:           Grid for slider for hours, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.
     *  * ui_minute_grid:         Grid for slider for minutes, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.
     *  * ui_second_grid:         Grid for slider for seconds, default is 0 - no grid. If grid is used, the labels bellow the slider will be displayed. Enter integer value for labels to be calculated.
     *  * theme:                  Which CSS styles to use from themes in config, or default theme will be used set in config. You can set this to null in order not to load any CSS
     *
     * Additional options
     *
     *  * ui_button_open:       HTML code for open picker button
     *  * ui_button_clear:      HTML code for clear value button
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetFormTime
     */
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('ui_time_format', dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture());
        $this->addOption('ui_control_type', 'slider');
        $this->addOption('ui_show_button_panel', true);
        $this->addOption('ui_step_hour', 1);
        $this->addOption('ui_step_minute', 1);
        $this->addOption('ui_step_second', 1);
        $this->addOption('ui_hour_grid', 0);
        $this->addOption('ui_minute_grid', 0);
        $this->addOption('ui_second_grid', 0);
        $this->addOption('theme', sfConfig::get('dm_dmTimePickerPlugin_default_theme'));

        $this->addOption('ui_button_open', '');
        $this->addOption('ui_button_clear', '');

        if (!isset($options['theme'])) {
            $options['theme'] = $this->getOption('theme');
        }

        sfWidgetFormDmTimePicker::parseThemeDefaultOptions($options, $this);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        $themes = sfConfig::get('dm_dmTimePickerPlugin_themes');
        $wrapperClass = '';
        if (
            isset($themes[$this->getOption('theme')]) &&
            isset($themes[$this->getOption('theme')]['wrapper_class']) &&
            $themes[$this->getOption('theme')]['wrapper_class']) {
            $wrapperClass = $themes[$this->getOption('theme')]['wrapper_class'];
        }



        return sprintf('<div %s %s %s %s %s %s %s %s %s %s %s %s class="sfWidgetFormDmTimePicker">%s</div>',
            sprintf('data-ui-time-format="%s"', $this->getOption('ui_time_format')),
            sprintf('data-ui-control-type="%s"', $this->getOption('ui_control_type')),
            sprintf('data-ui-show-button-panel="%s"', ($this->getOption('ui_show_button_panel')) ? 'true' : 'false'),
            sprintf('data-ui-step-hour="%s"', $this->getOption('ui_step_hour')),
            sprintf('data-ui-step-minute="%s"', $this->getOption('ui_step_minute')),
            sprintf('data-ui-step-second="%s"', $this->getOption('ui_step_second')),
            sprintf('data-ui-hour-grid="%s"', $this->getOption('ui_hour_grid')),
            sprintf('data-ui-minute-grid="%s"', $this->getOption('ui_minute_grid')),
            sprintf('data-ui-second-grid="%s"', $this->getOption('ui_second_grid')),
            sprintf('data-ui-wrapper-class="%s"', $wrapperClass),
            sprintf('data-ui-button-open="%s"', htmlentities($this->getOption('ui_button_open'))),
            sprintf('data-ui-button-clear="%s"', htmlentities($this->getOption('ui_button_clear'))),
            parent::render($name, $value, $attributes, $errors)
        );
    }

    public function getStylesheets() {
        if (is_null($this->getOption('theme'))) {
            return parent::getStylesheets();
        } else {
            $themes = sfConfig::get('dm_dmTimePickerPlugin_themes');
            if (
                isset($themes[$this->getOption('theme')]) &&
                isset($themes[$this->getOption('theme')]['css_files']) &&
                $themes[$this->getOption('theme')]['css_files']
            ) {
                $css = array();
                $files = (is_array($themes[$this->getOption('theme')]['css_files'])) ? $themes[$this->getOption('theme')]['css_files'] : array_map('trim', explode(',', $themes[$this->getOption('theme')]['css_files']));
                foreach ($files as $file) {
                    $css[$file] = null;
                }
                return array_merge(parent::getStylesheets(), $css);
            } else {
                return parent::getStylesheets();
            }
        }
    }
    
    public function getJavaScripts() {

        return array_merge(
                parent::getJavaScripts(),
                array(
                    'lib.ui-core',
                    'lib.ui-widget',
                    'lib.ui-mouse',
                    'lib.ui-slider',
                    'lib.ui-datepicker',
                    'dmDateTimePickerPlugin.tp-add-on',
                    'dmDateTimePickerPlugin.tp-add-on-i18n',
                    'dmDateTimePickerPlugin.time-picker'
                )
        );
    }

    public static function parseThemeDefaultOptions($options = array(), $instance)
    {
        $themes = sfConfig::get('dm_dmTimePickerPlugin_themes');
        if (isset($options['theme']) && isset($themes[$options['theme']]) && isset($themes[$options['theme']]['defaults'])) {
            $settings = $themes[$options['theme']]['defaults'];
            foreach ($settings as $key => $val) {
                if ($key == 'ui_time_format' || is_null($val)) continue;
                $instance->setOption($key, $val);
            }
            if (isset($settings['ui_time_format'])) {
                if (isset($settings['ui_time_format']['default'])) {
                    $instance->setOption('ui_time_format', $settings['ui_time_format']['default']);
                }
                if (
                    isset($settings['ui_time_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]) &&
                    $settings['ui_time_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]
                ) {
                    $instance->setOption('ui_time_format', $settings['ui_time_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]);
                }
            }
        }
    }

}
