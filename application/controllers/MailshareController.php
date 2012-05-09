<?php
class MailshareController extends Zend_Controller_Action {
	public function deleteAction() {
		
		$service = new Service_Mailshare();
		$service->delete($this->_getParam('id'));
		
		if ($this->_request->isXmlHttpRequest()) {
			exit;
		}
	}
	
	public function rotateAction() {
		
		$service = new Service_Mailshare();
		
		switch ($rotate = $this->_getParam('rotate')) {
			case 'left':
				$direction = Service_Mailshare::LEFT;
				break;
			case 'right':
				$direction = Service_Mailshare::RIGHT;
				break;
			default: break;
		}
		
		$service->rotateImage($this->_getParam('imagePath'), Service_Mailshare::LEFT);
		return exit(sha1(microtime(true)));
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