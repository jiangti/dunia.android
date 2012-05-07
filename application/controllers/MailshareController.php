<?php
class MailshareController extends Zend_Controller_Action {
	public function deleteAction() {
		
		$service = new Service_Mailshare();
		$service->delete($this->_getParam('id'));
		
		if ($this->_request->isXmlHttpRequest()) {
			exit;
		}
	}
	
	public function mergeAction() {
		
		$form = new Form_MailShare();

		if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
			$service = new Service_Mailshare();
			$pubRow = $service->merge($form->getValues());
			
			if ($this->_request->isXmlHttpRequest()) {
				echo json_encode($pubRow->toArray());
				exit;
			} else {
				$url = $this->view->url(array('controller' => 'pub', 'action' => 'share', 'id' => $pubRow->id));
				$this->_redirect($url);
			}
			
		}
	}
}