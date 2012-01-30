<?php
class Form_Deal extends Form_Abstract {
	public function init() {
		parent::init();
		
		$name = new Aw_Form_Element_Text('name');
		
		$name
			->setLabel('Name')
			->setRequired(true)
		;
		
		$location = new Zend_Form_Element_Textarea('location');
		$location
			->setLabel('Location')
			->setRequired(true)
			->setAttrib('style', 'height: 100px;')
		;
		
		$file = new Aw_Form_Element_File('file');
		$file->setMultiFile(3);
		
		
		$this->addElements(array($name, $location, $file));
		
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$subForm = new Form_Deal_Detail();
		$this->addSubForm($subForm, 'detail0');
		
		$subForm = new Form_Deal_Detail();
		$this->addSubForm($subForm, 'detail1');
		
		$subForm = new Form_Deal_Detail();
		$this->addSubForm($subForm, 'detail2');
		
		$subForm = new Form_Deal_Detail();
		$this->addSubForm($subForm, 'detail3');
	}
}