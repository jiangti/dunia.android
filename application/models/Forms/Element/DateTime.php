<?php

class Dol_Model_Forms_Element_DateTime extends Zend_Form_Element {

    protected $dateTime;

    protected $_displayValue = '';

    public $helper = 'formDateTime';

    public function setDisplayValue($value) {
        $this->_displayValue = $value;
        $this->setAttrib('displayValue', $this->_displayValue);
        return $this;
    }

    public function setHideTime($flag = true) {
        $this->getView()->hideTime = $flag;
        return $this;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDateTime() {
        return $this->dateTime;
    }

    /**
     * @return array | string
     */
    public function getValue() {
        return $this->isArray() ? $this->getValueAsArray() : $this->getValueAsString();
    }

    /**
     * @return string
     */
    public function getValueAsString() {
        return ($this->getDateTime()) ? $this->getDateTime()->format('Y-m-d H:i:s') : parent::getValue();
    }

    /**
     * @return array
     */
    public function getValueAsArray() {
        if ($this->getDateTime()) {
            return array(
                'date' => $this->getDateTime()->format('Y-m-d'),
                'time' => $this->getDateTime()->format('H:i:s')
            );
        }

        return array(
            'date' => null,
            'time' => null
        );
    }

    /**
     * @param string|array|DateTime data
     * @return self
     */
    public function setValue($value) {
        try {
            if (is_string($value) && strlen($value)) {
                $this->dateTime = new DateTime($value); // should be in MySQL-like format
            }
            elseif (is_array($value)) {
                $value['date'] = ifx($value, 'date', '');
                $value['time'] = ifx($value, 'time', '00:00');

                $value = sprintf('%s %s', $value['date'], $value['time']); // 11/12/2010 22:22
                $this->dateTime = DateTime::createFromFormat('d/m/Y H:i', $value);
            }
            elseif ($value instanceof DateTime) {
                $this->dateTime = $value;
            }
        }
        catch (Exception $e) {
            // Just do nothing.
        }

        return parent::setValue($value);
    }
}
