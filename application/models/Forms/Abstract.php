<?php

abstract class Dol_Model_Forms_Abstract extends Zend_Form {
	
	public function addElement($element, $name = null, $options = null) {
		
		$element->setDecorators(array(
			'ViewHelper',
			'Description',
			'Errors',
			new Dol_Model_Forms_Decorator_Element()
		));
		
		parent::addElement($element, $name, $options);
	}
	
	public function init() {
        parent::init();

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array(
                'tag' => 'div',
                'style' => 'clear: both; display: block; width: 100%;',
                'placement' => Zend_Form_Decorator_HtmlTag::APPEND)
            ),
            'Form'
        ));
	}
}