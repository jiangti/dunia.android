<?php

class Dol_Model_Forms_Deal extends Dol_Model_Forms_Abstract {
	
	public function init() {
		$this->setAction('/venue/new-deal');
		
		$this->addElement(new Zend_Form_Element_Text('name', 
							array('label'    => 'Name of the Deal',
								  'required' => true)));
							
		$this->addElement(new Zend_Form_Element_Textarea('description', array(
			'label' => 'Description',
			'rows'  => 7
		)));

		$this->addElement(new Zend_Form_Element_MultiCheckbox('days', array(
			'label' => 'Days',
			'multiOptions' => array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
			'separator'	   => ' '
		)));

		$this->addElement(new Dol_Model_Forms_Element_Time('timeStart', array(
			'label' => 'Start time',
			'value' => date('H:i')
		)));

		$this->addElement(new Dol_Model_Forms_Element_Time('timeEnd', array(
			'label' => 'End time',
			'value' => date('H:i')
		)));
		
		$this->addElement(new Dol_Model_Forms_Element_AutocompleteCheckbox('types', array( 
			'label' => 'Categories',
			'uri'	=> '/venue/autocomplete-categories'
		)));

							
		$this->addElement(new Zend_Form_Element_Submit('submit', 
						    array('label' => 'Save')));
						   
		$this->addElement(new Zend_Form_Element_Hidden('venue'));
		$this->addElement(new Zend_Form_Element_Hidden('id'));
		
		parent::init();
	}
	
	public function setDefaults($defaults) {
		if (isset($defaults['days'])) {
			$days = array();
			foreach ($defaults['days'] as $day) {
				if ($day instanceof Dol_Model_Entity_DealHasDay) {
					$days[] = $day->day;
				}
			}
			$defaults['days'] = $days;
		}

		if (isset($defaults['venue']) && $defaults['venue'] instanceof Dol_Model_Entity_Venue) {
			$defaults['venue'] = $defaults['venue']->id;
		}
		
		if (isset($defaults['types'])) {
			if($defaults['types']) {
				$defaults['types'] = $defaults['types']->toArray();
			} else {
				$defaults['types'] = array();
			}
		}
		
		return parent::setDefaults($defaults);
	}
}