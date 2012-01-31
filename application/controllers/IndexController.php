<?php
class IndexController extends Zend_Controller_Action {
    
    const DEFAULT_LATITUDE  = -33.8757;
    const DEFAULT_LONGITUDE = 151.206;
    
	public function indexAction() {
		
		$this->_helper->layout->setLayout('map');
		
		$form = new Form_Map();
		
		$this->view->form = $form;
		
	}
	
	public function listAction() {
	    $this->view->pubs = $this->getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	public function locateAction() {
	    $this->view->pubs = $this->getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	    $this->_helper->layout()->disableLayout();
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
	    $this->view->pubs = $this->getPubs($this->_getParam('latitude'), $this->_getParam('longitude'));
	}
	
	protected function getPubs($latitude, $longitude) {
	    $pubTable = new Model_DbTable_Pub();
	    $db       = $pubTable->getAdapter();
	    
	    if (!$latitude) {
	        $latitude = self::DEFAULT_LATITUDE;
	    }
	    if (!$longitude) {
	        $longitude = self::DEFAULT_LONGITUDE;
	    }
	    
	    $select = $db->select()
	    ->from(array('p' => 'pub'))
	    ->join(array('a' => 'address'),
	           'p.idAddress = a.id',
	           array('longitude' => 'longitude', 'latitude', 'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$latitude')) * cos(radians(latitude)) * cos(radians(longitude) - radians('$longitude')) + sin(radians('$latitude')) * sin(radians(latitude))), 2)")))
	    ->order('distance');
	    
	    return $db->fetchAll($select);
	}
	
	public function shareAction() {
		
		
		$form = new Form_Deal('deal');
		
		$pub = null;
		
		if ($id = $this->_request->getParam('edit')) {
			if ($pub = Model_DbTable_Pub::retrieveById($id)) {
				$form->setRecord($pub);
			}
		}
		
		if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
			$data = $form->getValues();
			
			$form->file->receive();
			
			$data['file_tmpfile'] = $form->file->getFileName();
			
			$service = new Service_Pub();
			$service->savePubFromShareArray($data, $pub);
			
		}
		
		$this->view->form = $form;
	}
}

