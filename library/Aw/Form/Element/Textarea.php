<?php
class Aw_Form_Element_Textarea extends Zend_Form_Element_Textarea {
public function init() {
		parent::init();
		if ($this->isRequired()) {
			$this->setAttrib('required', 'required');
		}
	}
	
	public function setReadOnly($flag = true) {
		if ($flag) {
			$this
			->setIgnore(true)
			->setAttrib('readonly', 'readonly')
			;
		} else {
			$this
			->setIgnore(false)
			->setAttrib('readonly', null)
			;
		}
		 
		return $this;
	}
}