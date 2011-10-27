<?php

class Dol_Model_Forms_Address extends Zend_Form {

	public function init() {

		$this->addElement(new Zend_Form_Element_Text('address1',
							array('label'    => 'Address Line 1',
								  'required' => true)));

		$this->addElement(new Zend_Form_Element_Text('address2',
							array('label' => 'Address Line 2')));

		$this->addElement(new Zend_Form_Element_Text('city',
							array('label' => 'Suburb')));

		$this->addElement(new Zend_Form_Element_Text('state',
							array('label' => 'State')));

		$this->addElement(new Zend_Form_Element_Text('postCode',
							array('label' => 'Post Code')));

		$this->addElement(new Zend_Form_Element_Text('country',
							array('label' => 'Country')));

		$this->addElement(new Zend_Form_Element_Submit('submit',
						    array('label' => 'Save')));

		$this->addElement(new Zend_Form_Element_Hidden('id'));

	}
}