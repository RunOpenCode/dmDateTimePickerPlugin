<?php
/**
 * Description of sfWidgetFormDateTimePicker
 *
 * @author TheCelavi
 */
class sfWidgetFormDmDateTimePicker extends sfWidgetFormDateTime {

    protected $defaultOptions = array(
        'ui_show_button_panel'=> true,
        'ui_button_open' => '',
        'ui_button_clear' => ''
    );

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options = array(), $attributes = array());

        // Option for both
        $this->addOption('ui_show_button_panel', true);
        $this->addOption('theme', sfConfig::get('dm_dmDateTimePickerPlugin_default_theme'));
        $this->addOption('ui_button_open', '');
        $this->addOption('ui_button_clear', '');

        // Date options
        $this->addOption('ui_date_format', dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture());
        $this->addOption('ui_number_of_months', 1);
        $this->addOption('ui_change_month', true);
        $this->addOption('ui_change_year', true);
        $this->addOption('ui_constrain_input', true);

        // Time options
        $this->addOption('ui_time_format', dmContext::getInstance()->getServiceContainer()->getService('user')->getCulture());
        $this->addOption('ui_control_type', 'slider');
        $this->addOption('ui_step_hour', 1);
        $this->addOption('ui_step_minute', 1);
        $this->addOption('ui_step_second', 1);
        $this->addOption('ui_hour_grid', 0);
        $this->addOption('ui_minute_grid', 0);
        $this->addOption('ui_second_grid', 0);

        if (!isset($options['theme'])) {
            $options['theme'] = $this->getOption('theme');
        }

        $this->parseThemeDefaultOptions($options);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        $wrapperClass = '';

        $themes = sfConfig::get('dm_dmDatePickerPlugin_themes');
        if (
            isset($themes[$this->getOption('theme')]) &&
            isset($themes[$this->getOption('theme')]['wrapper_class']) &&
            $themes[$this->getOption('theme')]['wrapper_class']) {
            $wrapperClass = $themes[$this->getOption('theme')]['wrapper_class'];
        }

        $themes = sfConfig::get('dm_dmTimePickerPlugin_themes');
        if (
            isset($themes[$this->getOption('theme')]) &&
            isset($themes[$this->getOption('theme')]['wrapper_class']) &&
            $themes[$this->getOption('theme')]['wrapper_class']) {
            if ($wrapperClass) {
                // Merge wrapper classes
                $wrapperClass .= ' ' . $themes[$this->getOption('theme')]['wrapper_class'];
            } else {
                $wrapperClass = $themes[$this->getOption('theme')]['wrapper_class'];
            }
        }

        $wrapperClass = implode(' ',array_unique(array_map('trim',explode(' ', $wrapperClass))));

        return sprintf('<div %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s %s class="sfWidgetFormDmDateTimePicker">%s</div>',
            // Date
            sprintf('data-ui-date-format="%s"', $this->getOption('ui_date_format')),
            sprintf('data-ui-number-of-months="%s"', $this->getOption('ui_number_of_months')),
            sprintf('data-ui-show-button-panel="%s"', ($this->getOption('ui_show_button_panel')) ? 'true' : 'false'),
            sprintf('data-ui-change-month="%s"', ($this->getOption('ui_change_month')) ? 'true' : 'false'),
            sprintf('data-ui-change-year="%s"', ($this->getOption('ui_change_year')) ? 'true' : 'false'),
            sprintf('data-ui-constrain-input="%s"', ($this->getOption('constrain_input')) ? 'true' : 'false'),
            // Time
            sprintf('data-ui-time-format="%s"', $this->getOption('ui_time_format')),
            sprintf('data-ui-control-type="%s"', $this->getOption('ui_control_type')),
            sprintf('data-ui-show-button-panel="%s"', ($this->getOption('ui_show_button_panel')) ? 'true' : 'false'),
            sprintf('data-ui-step-hour="%s"', $this->getOption('ui_step_hour')),
            sprintf('data-ui-step-minute="%s"', $this->getOption('ui_step_minute')),
            sprintf('data-ui-step-second="%s"', $this->getOption('ui_step_second')),
            sprintf('data-ui-hour-grid="%s"', $this->getOption('ui_hour_grid')),
            sprintf('data-ui-minute-grid="%s"', $this->getOption('ui_minute_grid')),
            sprintf('data-ui-second-grid="%s"', $this->getOption('ui_second_grid')),
            // Both
            sprintf('data-ui-wrapper-class="%s"', $wrapperClass),
            sprintf('data-ui-button-open="%s"', htmlentities($this->getOption('ui_button_open'))),
            sprintf('data-ui-button-clear="%s"', htmlentities($this->getOption('ui_button_clear'))),

            parent::render($name, $value, $attributes, $errors)
        );
    }

    public function getStylesheets() {

        $stylesheets = array();

        if (is_null($this->getOption('theme'))) {
            return parent::getStylesheets();
        } else {
            $themes = sfConfig::get('dm_dmDatePickerPlugin_themes');
            if (
                isset($themes[$this->getOption('theme')]) &&
                isset($themes[$this->getOption('theme')]['css_files']) &&
                $themes[$this->getOption('theme')]['css_files']
            ) {
                $files = (is_array($themes[$this->getOption('theme')]['css_files'])) ? $themes[$this->getOption('theme')]['css_files'] : array_map('trim', explode(',', $themes[$this->getOption('theme')]['css_files']));
                foreach ($files as $file) {
                    $stylesheets[$file] = null;
                }
            }

            // Time picker
            $themes = sfConfig::get('dm_dmTimePickerPlugin_themes');
            if (
                isset($themes[$this->getOption('theme')]) &&
                isset($themes[$this->getOption('theme')]['css_files']) &&
                $themes[$this->getOption('theme')]['css_files']
            ) {
                $files = (is_array($themes[$this->getOption('theme')]['css_files'])) ? $themes[$this->getOption('theme')]['css_files'] : array_map('trim', explode(',', $themes[$this->getOption('theme')]['css_files']));
                foreach ($files as $file) {
                    $stylesheets[$file] = null;
                }
            }
        }


        return array_merge(
            parent::getStylesheets(),
            $stylesheets
        );
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
                'lib.ui-i18n',
                'dmDateTimePickerPlugin.tp-add-on',
                'dmDateTimePickerPlugin.tp-add-on-i18n',
                'dmDateTimePickerPlugin.date-time-picker'
            )
        );
    }

    protected function parseThemeDefaultOptions($options)
    {
        sfWidgetFormDmDatePicker::parseThemeDefaultOptions($options, $this);
        sfWidgetFormDmTimePicker::parseThemeDefaultOptions($options, $this);
        // Override parsed theme options
        $this->setOption('ui_show_button_panel', $this->defaultOptions['ui_show_button_panel']);
        $this->setOption('ui_button_open', $this->defaultOptions['ui_button_open']);
        $this->setOption('ui_button_clear', $this->defaultOptions['ui_button_clear']);

        $themes = sfConfig::get('dm_dmDateTimePickerPlugin_themes');
        if (isset($options['theme']) && isset($themes[$options['theme']]) && isset($themes[$options['theme']]['defaults'])) {
            $settings = $themes[$options['theme']]['defaults'];
            foreach ($settings as $key => $val) {
                if ($val) $this->setOption($key, $val);
            }
        }

    }
    
}
