<?php
abstract class Aw_Form_User_Login_Abstract extends Aw_Form_Abstract {
	public function init() {
		parent::init();
		$username = new Aw_Form_Element_Text('username');
		$username
			->setLabel('Username')
			->setRequired(true)
		;
		$password = new Aw_Form_Element_Password('password');
		$password
			->setLabel('Password')
			->setRequired(true)
		;

		$rememberMe = new Aw_Form_Element_Checkbox('rememberMe');
		$rememberMe
			->setLabel('Remember Me');
		;

		$this->submit->setLabel('Login');

		$this->addElements(array($username, $password, $rememberMe));
	}
}