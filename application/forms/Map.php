<?php
class Form_Map extends Zend_Form {
	public function init() {
		
		parent::init();
		
		$this->setAttrib('id', 'searchForm');
		
		$location = new Zend_Form_Element_Text('location');
		$location
//			->setLabel('Location')
			->setAttrib('class', 'location-suggest')
			->setAttrib('placeholder', 'Go to other location...')
		;
		
		$location->removeDecorator('Label');
		
		$time = new Aw_Form_Element_Time('time');
		$time
			->setLabel('Time')
		;
		
		$elements = array(
			$location,
			$time,
		);
		
		$this->addElements($elements);
		
	}
	
	public function loadDefaultDecorators() {
		$return = parent::loadDefaultDecorators();
		
		foreach ($this->getElements() as $index => $element) {
			if ($label = $element->getDecorator('label')) {
				$label->setOptions(array('tag' => 'span'));
			}
			if ($viewHelper = $element->getDecorator('htmlTag')) {
				$viewHelper->setOptions(array('tag' => 'span'));
			}
		}
		return $return;
	}
	
}