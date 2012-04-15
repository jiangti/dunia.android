<?php
class MailshareController extends Zend_Controller_Action {
	public function deleteAction() {
		
		$service = new Service_Mailshare();
		$service->delete($this->_getParam('id'));
		
		if ($this->_request->isXmlHttpRequest()) {
			exit;
		}
	}
}