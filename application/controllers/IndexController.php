<?php
class IndexController extends Zend_Controller_Action {
	public function indexAction() {
		
		$form = new Form_Map();
		
		$this->view->form = $form;
		
	}
	
	public function listAction() {
	    $pubTable         = new Model_DbTable_Pub();
	    $db               = $pubTable->getAdapter();
	    
	    $lat = '-33.8757';
	    $lon = '151.206';
	    
	    $select = $db->select()
	        ->from(array('p' => 'pub'))
	        ->join(array('a' => 'address'), 
	        	   'p.idAddress = a.id', 
	        	   array('longitude' => 'longtitude', 'latitude', 'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$lat')) * cos(radians(latitude)) * cos(radians(longtitude) - radians('$lon')) + sin(radians('$lat')) * sin(radians(latitude))), 2)")))
            ->order('distance');
	    
	    $this->view->pubs = $db->fetchAll($select);
	}
	
	public function pubAction() {
	    if ($id = $this->_getParam('id')) {
	        $this->view->pub = Model_DbTable_Pub::retrieveById($id);
	    }
	}
	
	public function mapAction() {
	    $pubTable         = new Model_DbTable_Pub();
	    $db               = $pubTable->getAdapter();
	     
	    $lat = '-33.8757';
	    $lon = '151.206';
	     
	    $select = $db->select()
	    ->from(array('p' => 'pub'))
	    ->join(array('a' => 'address'),
	    	        	   'p.idAddress = a.id', 
	    array('longitude' => 'longtitude', 'latitude', 'distance' => new Zend_Db_Expr("ROUND(6371000 * acos(cos(radians('$lat')) * cos(radians(latitude)) * cos(radians(longtitude) - radians('$lon')) + sin(radians('$lat')) * sin(radians(latitude))), 2)")))
	    ->order('distance');
	     
	    $this->view->pubs = $db->fetchAll($select);
	}
}

