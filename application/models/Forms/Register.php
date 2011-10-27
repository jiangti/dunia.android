<?php

class Dol_Model_Forms_Register extends Dol_Model_Forms_Abstract {

    protected $_em;

    public function __construct($options) {
        $this->_em = Zend_Registry::get('EntityManager');

        parent::__construct($options);
    }

    public function init() {
    	$this->setAction('/index/register');
    	$this->addAttribs(array('class' => 'rounded half'));
		
        // Username
		$this->addElement(new Zend_Form_Element_Text('username',
			array('label'    => 'Login',
				  'required' => true)
		));


		//Password + password confirm
		$field = new Zend_Form_Element_Password('password',
            array('label' => 'Password')
        );

        $field->addFilter(new Zend_Filter_StringTrim())
              ->addValidator(new Zend_Validate_NotEmpty());

        $this->addElement($field);



		$field = new Zend_Form_Element_Password('confirmPassword',
            array('label' => 'Confirm Password')
        );

        $field->addFilter(new Zend_Filter_StringTrim())
              ->addValidator(new Zend_Validate_Identical('password'));
		$this->addElement($field);

		// Email Address
		$field = new Zend_Form_Element_Text(
		    'emailAddress',
            array('label'    => 'Email',
                  'required' => true));

        $field->addValidator(new Zend_Validate_EmailAddress());
        $this->addElement($field);

        $this->addElement(new Zend_Form_Element_Text(
            'firstName',
            array('label'    => 'First Name',
                  'required' => true)
        ));

        $this->addElement(new Zend_Form_Element_Text(
            'lastName',
            array('label'    => 'Last Name',
                  'required' => true)
        ));

		$this->addElement(new Zend_Form_Element_Submit('submit',
						    array('label' => 'Register')));

		parent::init();
	}


	public function isValid($data) {
	    $usernameExists = Dol_Model_Entity_User::exists($data['username'], $this->_em)          ? true : false;
	    $emailExists    = Dol_Model_Entity_User::existsEmail($data['emailAddress'], $this->_em) ? true : false;

	    if(isset($data['username']) && !$usernameExists && !$emailExists) {
	        return parent::isValid($data);
	    }

	    else if ($usernameExists) {
    	    $this->getElement('username')->setErrors(array("username '". $data['username'] ."' already exists"));
            return false;
	    }

	    else {
	        $this->getElement('emailAddress')->setErrors(array("Email Address already registered"));
            return false;
	    }

	}
}