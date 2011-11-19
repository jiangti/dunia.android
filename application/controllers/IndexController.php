<?php
class IndexController extends Zend_Controller_Action {
	public function indexAction() {
		
		$form = new Form_Map();
		
		$this->view->form = $form;
		
	}
}

