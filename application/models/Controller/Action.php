<?php
class Model_Controller_Action extends Zend_Controller_Action {
	protected function _getUser() {
		return Model_User::getInstance();
	}
}