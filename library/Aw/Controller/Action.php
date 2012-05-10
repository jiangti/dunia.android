<?php
class Choc_Controller_Action extends Zend_Controller_Action
{
	public function _jsRedirect($url) {
		echo sprintf('<script>window.location = "%s"</scirpt>', $url); exit;
	}
	
	protected function _getDefaultView() {
		$view = clone $this->view;
		return $view->setScriptPath(APPLICATION_PATH . '/views/scripts');
	}
	
	public function init() {
		if ($this->_request->isXmlHttpRequest()) {
			$this->_helper->layout->disableLayout();
		}
	}
}