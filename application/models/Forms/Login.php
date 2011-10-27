<?php

class Dol_Model_Forms_Login extends Dol_Model_Forms_Abstract {
	public function init() {

		$this->setAction('/index/login');
		$this->addAttribs(array('class' => 'rounded half'));
		
		$this->addElement(new Zend_Form_Element_Text('username', 
			array('label'    => 'Login',
				  'required' => true)
		));
							
		$this->addElement(new Zend_Form_Element_Password('password', 
			array('label' => 'Password')
		));
							
		$this->addElement(new Zend_Form_Element_Submit('submit', 
		    array('label' => 'Login')
		));
		
		parent::init();
	}
}