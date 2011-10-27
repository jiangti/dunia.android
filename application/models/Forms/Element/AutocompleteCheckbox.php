<?php

/**
 * Autocomplete element.
 *
 * [Text field: Name]
 * [Hidden field: Integer ID]
 *
 */
class Dol_Model_Forms_Element_AutocompleteCheckbox extends Zend_Form_Element_Xhtml {

    public $helper = 'formAutocompleteCheckbox';

    protected $_displayValue = '';

    /**
     *  All pretty standard; just inherit isValid.
     *
     */
    public function setDisplayValue($value) {
        $this->_displayValue = "$value";
        $this->setAttrib('displayValue', $this->_displayValue);
    }
}

