<?php

/**
 * Replaces standard Symfony UI date with jQuery date picker
 *
 * @author TheCelavi
 */
class sfWidgetFormDmDatePicker extends sfWidgetFormDate
{

    /**
     * Configures the current widget.
     *
     * Available options:
     *
     *  * format:       The date format string (%month%/%day%/%year% by default)
     *  * years:        An array of years for the year select tag (optional)
     *                  Be careful that the keys must be the years, and the values what will be displayed to the user
     *  * months:       An array of months for the month select tag (optional)
     *  * days:         An array of days for the day select tag (optional)
     *  * can_be_empty: Whether the widget accept an empty value (true by default)
     *  * empty_values: An array of values to use for the empty value (empty string for year, month, and day by default)
     *
     * Available jQuery UI Date picker options:
     *  * ui_date_format:       Date formats, see: http://api.jqueryui.com/datepicker/#utility-formatDate, default is date format for current culture
     *  * ui_number_of_months:  Number of months to display, default is 1
     *  * ui_show_button_panel: Whether to show buttons Today and Done, default is true
     *  * ui_change_month:      Whether to show month drop-down, default is true
     *  * ui_change_year:       Whether to show year drop-down, default is true
     *  * theme:                Which CSS styles to use from themes in config, or default theme will be used set in config. You can set this to null in order not to load any CSS
     *  * ui_constrain_input:   Enable only to input characters defined in date format, default is true
     *
     * Additional options
     *
     *  * ui_button_open:       HTML code for open picker button
     *  * ui_button_clear:      HTML code for clear value button
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetFormDate
     */
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options = array(), $attributes = array());

        $this->addOption('ui_date_format', dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture());
        $this->addOption('ui_number_of_months', 1);
        $this->addOption('ui_show_button_panel', true);
        $this->addOption('ui_change_month', true);
        $this->addOption('ui_change_year', true);
        $this->addOption('ui_constrain_input', true);
        $this->addOption('theme', sfConfig::get('dm_dmDatePickerPlugin_default_theme'));

        $this->addOption('ui_button_open', '');
        $this->addOption('ui_button_clear', '');

        if (!isset($options['theme'])) {
            $options['theme'] = $this->getOption('theme');
        }

        sfWidgetFormDmDatePicker::parseThemeDefaultOptions($options, $this);
    }


    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $themes = sfConfig::get('dm_dmDatePickerPlugin_themes');
        $wrapperClass = '';
        if (
            isset($themes[$this->getOption('theme')]) &&
            isset($themes[$this->getOption('theme')]['wrapper_class']) &&
            $themes[$this->getOption('theme')]['wrapper_class']) {
            $wrapperClass = $themes[$this->getOption('theme')]['wrapper_class'];
        }

        return sprintf('<div %s %s %s %s %s %s %s %s %s class="sfWidgetFormDmDatePicker">%s</div>',
            sprintf('data-ui-date-format="%s"', $this->getOption('ui_date_format')),
            sprintf('data-ui-number-of-months="%s"', $this->getOption('ui_number_of_months')),
            sprintf('data-ui-show-button-panel="%s"', ($this->getOption('ui_show_button_panel')) ? 'true' : 'false'),
            sprintf('data-ui-change-month="%s"', ($this->getOption('ui_change_month')) ? 'true' : 'false'),
            sprintf('data-ui-change-year="%s"', ($this->getOption('ui_change_year')) ? 'true' : 'false'),
            sprintf('data-ui-constrain-input="%s"', ($this->getOption('constrain_input')) ? 'true' : 'false'),
            sprintf('data-ui-wrapper-class="%s"', $wrapperClass),
            sprintf('data-ui-button-open="%s"', htmlentities($this->getOption('ui_button_open'))),
            sprintf('data-ui-button-clear="%s"', htmlentities($this->getOption('ui_button_clear'))),
            parent::render($name, $value, $attributes, $errors)
        );
    }

    public function getStylesheets()
    {
        if (is_null($this->getOption('theme'))) {
            return parent::getStylesheets();
        } else {
            $themes = sfConfig::get('dm_dmDatePickerPlugin_themes');
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

    public function getJavaScripts()
    {
        return array_merge(
            parent::getJavaScripts(),
            array(
                'lib.ui-core',
                'lib.ui-datepicker',
                'lib.ui-i18n',
                'dmDateTimePickerPlugin.date-picker'
            )
        );
    }

    public static function parseThemeDefaultOptions($options = array(), $instance)
    {
        $themes = sfConfig::get('dm_dmDatePickerPlugin_themes');
        if (isset($options['theme']) && isset($themes[$options['theme']]) && isset($themes[$options['theme']]['defaults'])) {
            $settings = $themes[$options['theme']]['defaults'];
            foreach ($settings as $key => $val) {
                if ($key == 'ui_date_format' || is_null($val)) continue;
                $instance->setOption($key, $val);
            }
            if (isset($settings['ui_date_format'])) {
                if (isset($settings['ui_date_format']['default'])) {
                    $instance->setOption('ui_date_format', $settings['ui_date_format']['default']);
                }
                if (
                    isset($settings['ui_date_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]) &&
                    $settings['ui_date_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]
                ) {
                    $instance->setOption('ui_date_format', $settings['ui_date_format'][dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture()]);
                }
            }
        }
    }

}