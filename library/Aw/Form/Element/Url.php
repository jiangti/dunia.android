<?php
class Aw_Form_Element_Url extends Aw_Form_Element_Text {
	public function init() {
		parent::init();
		$this->addValidator(new Aw_Validate_Url());
	}
}