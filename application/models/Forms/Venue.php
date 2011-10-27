<?php

class Dol_Model_Forms_Venue extends Zend_Form {

	public function init() {

		$this->addElement(new Zend_Form_Element_Text('name',
							array('label'    => 'Venue Name',
								  'required' => true)));

		$this->addElement(new Zend_Form_Element_Textarea('description',
							array('label' => 'Description')));

		$this->addElement(new Zend_Form_Element_Submit('submit',
						    array('label' => 'Save')));

	}
}