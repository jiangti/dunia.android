<?php
class Aw_Form_Element_File extends Zend_Form_Element_File {
	public function loadDefaultDecorators() {
		parent::loadDefaultDecorators();
		
		$description = $this->getDecorator('description');
		$description->setOption('tag', 'span');
		
	}
}