<?php

class dmDateTimePickerPluginConfiguration extends sfPluginConfiguration {

    /**
     * @see sfPluginConfiguration
     */
    public function initialize() {        
        $this->dispatcher->connect('dm.form_generator.widget_subclass', array($this, 'listenToFormGeneratorWidgetSubclassEvent'));        
    }
    
    public function listenToFormGeneratorWidgetSubclassEvent(sfEvent $e, $subclass) {        
        if ($this->isDateTimePickerColumn($e['column'])) $subclass = 'DmDateTimePicker';
        elseif ($this->isTimePickerColumn($e['column'])) $subclass = 'DmTimePicker';
        elseif ($this->isDatePickerColumn($e['column'])) $subclass = 'DmDatePicker';
        return $subclass;
    }

    protected function isDateTimePickerColumn(sfDoctrineColumn $column) {        
        return false !== strpos(dmArray::get($column->getTable()->getColumnDefinition($column->getName()), 'extra', ''), 'datetimepicker');
    }
    
    protected function isDatePickerColumn(sfDoctrineColumn $column) {
        return false !== strpos(dmArray::get($column->getTable()->getColumnDefinition($column->getName()), 'extra', ''), 'datepicker');
    }
    
    protected function isTimePickerColumn(sfDoctrineColumn $column) {
        return false !== strpos(dmArray::get($column->getTable()->getColumnDefinition($column->getName()), 'extra', ''), 'timepicker');
    }

}