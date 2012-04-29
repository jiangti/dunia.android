<?php
class Aw_Form_Element_Email extends Aw_Form_Element_Text {
	public function init() {
		parent::init();
		$this->addValidator('EmailAddress');
	}
}