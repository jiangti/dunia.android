<?php
class Form_Deal extends Aw_Form_Abstract {
	public function init() {
		parent::init();
		
		$location = new Zend_Form_Element_Textarea('location');
		$location
			->setLabel('Location')
			->setRequired(true)
			->setAttrib('style', 'height: 100px;')
		;
		
		for ($i = 0; $i < 3; $i++) {
			$var = 'file' . $i;
			$$var = new Aw_Form_Element_File('file' . $i);
		}
		
		$file0->setRequired(true);
		
		$this->addElements(array($location, $file0, $file1, $file2));
	}
}