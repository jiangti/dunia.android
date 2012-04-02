<?php
class IndexController extends Model_Controller_Action {
    
	public function indexAction() {
		$this->_helper->layout->setLayout('map');
		$form = new Form_Map();
		$this->view->form = $form;
		
		$user = $this->_getUser();
		
		$this->view->lat = $user->getLat();
		$this->view->long = $user->getLong();
		
		
	}
	
	public function listAction() {
        $service = new Service_Pub();
	    $this->view->pubs = $service->findPubByLatLong($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	public function locateAction() {
        $service = new Service_Pub();
	    $this->view->pubs = $service->findPubByLatLong($this->_getParam('latitude'), $this->_getParam('longitude'));
	    $this->_helper->layout()->disableLayout();
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
        $service = new Service_Pub();
        $this->view->pubs = $service->findPubByLatLong($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	protected function _getPubs($latitude, $longitude) {
	    throw new Exception("Deprecated function, use the Service_Pub instead for finding and filtering.");
	}
	
	
	
	public function landingAction() {
        $this->_helper->layout()->disableLayout();
    }
}

