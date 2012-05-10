<?php
class Choc_Form_Decorator_ViewHelper extends Zend_Form_Decorator_ViewHelper {
	public function getElementAttribs() {
		$attribs = parent::getElementAttribs();
		
		$errors = $this->_element->getMessages();
		if ($errors) {
			
			$class = $this->_element->getAttrib('class');
			$classes = explode(" ", trim($class . ' field-error'));
			$this->_element->setAttrib('class', implode(" ", array_unique($classes)));
			$attribs = parent::getElementAttribs();
		}
		
		return $attribs;
	}
}