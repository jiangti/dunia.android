<?php
trait Aw_Form_ELement_Trait {
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