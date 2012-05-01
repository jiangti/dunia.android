<?php
abstract class Aw_Form_User_Register_Abstract extends Aw_Form_Abstract {
	public function init() {
		parent::init();

		$rule1 = new Zend_Validate_Regex(array('pattern' => '/^([a-zA-Z0-9\-\.]+)$/'));
		$rule1->setMessage('Please use only number, alphabets, "." and "-"', Zend_Validate_Regex::NOT_MATCH);

		$username = new Aw_Form_Element_Text('username');
		$username
			->setLabel('Username')
			->setRequired(true)
			->addValidator($rule1)
		;

		$email = new Aw_Form_Element_Email('email');
		$email
			->setLabel('Email')
			->setRequired(true)
		;

		$rule1 = new Zend_Validate_StringLength();
		$rule1->setMin(8);

		$password = new Aw_Form_Element_Password('password');
		$password
			->addValidator($rule1)
			->setLabel('Password')
			->setRequired(true)
			->setDescription('at least 8 character long.')
		;

		$repassword = new Aw_Form_Element_Password('repassword');
		$repassword
			->setLabel('Re-enter Password')
			->setRequired(true)
		;

		$rule1 = new Zend_Validate_Identical();
		$rule1
			->setToken('repassword')
			->setMessage('The passwords provided must be the same.', Zend_Validate_Identical::NOT_SAME)
		;

		$password->addValidator($rule1);

		$this->submit->setValue('register')->setLabel('Register');


		$this->addElements(array($username, $email, $password, $repassword));
	}
}