<?php

require_once 'Zend/Form/Decorator/Abstract.php';

class Dol_Model_Forms_Decorator_Element extends Zend_Form_Decorator_Abstract {

    protected $_placement = null;
    protected $_DecoratorOptions = array();

    public function __construct( $options = null ) {
        if (is_array($options)) {
            $this->_DecoratorOptions = $options;
        }

        parent::__construct($options);
    }

    public function render($content) {
        $elementName = $this->getElement()->getName();
        $elementId = $this->getElement()->getId();
        $elementLabel = $this->getElement()->getLabel();
        $redAsterisk = $this->getElement()->isRequired() ? '<span class="required" title="Required">*</span>&nbsp;' : '';
        $element = $this->getElement();
        $elementId = $this->getElement()->getId();

        $output = '';

        // hide the label for all submit buttons
        if ($element instanceof Zend_Form_Element_Submit) {
            $elementLabel = '';
        } 
        
        if ($element instanceof Zend_Form_Element_Hidden) {
            return $content;
        } else {
            $output = '<div class="elementContainer ' . $this->getOption('class') . '">'
            	. '<div class="label"><label for="' . $elementId . '">' . $redAsterisk . $elementLabel . '</label></div>'
                . '<div class="field">' . $content . '</div>'
                . '</div><div class="clear"></div>';
        }

        return $output;
    }

}
