<?php
class Form_Map extends Zend_Form {
	public function init() {
		
		$this->setDisableLoadDefaultDecorators(true);
		
		parent::init();
		$location = new Zend_Form_Element_Text('location');
		$location
			->setLabel('Location')
		;
		$time = new Aw_Form_Element_Time('time');
		$time
			->setLabel('Time')
		;
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit
			->setLabel('Find')
		;
		
		$elements = array(
			$location,
			$time,
			$submit
		);
		
		$this->addElements($elements);
		
		$this->setDecorators(array(
			'FormElements',
			'Form'
		));	
		
		$this->setElementDecorators(array(
			'Label',
			'ViewHelper',
			array('HtmlTag', array('tag' => 'span'))
		));
		
		$submit->removeDecorator('label');
	}
	
}