<?php
abstract class Aw_Validate_Abstract extends Zend_Validate_Abstract {
	protected $_element;
	
	public function setElement(Zend_Form_Element $element) {
		$this->_element = $element;
		return $this;
	}
	
	/**
	 *
	 */
	public function getElement() {
		return $this->_element;
	}
}