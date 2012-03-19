<?php
class FoursquareController extends Model_Controller_Action {
	
    /**
     * @var Aw_Service_Foursquare
     */
    protected $foursquare;
    
    public function init() {
        ini_set('max_execution_time', 600);
        
        $bootstrap  = $this->getInvokeArg('bootstrap');
        $this->foursquare = $bootstrap->getPluginResource('foursquare')->getFoursquare();
    }
    
    /**
     * Lets make controller thin, services fat, models fat.
     */
	public function validateAction() {
	    if ($this->_request->isPost()) {
	        foreach ($this->_getParam('pub') as $idPub => $idFoursquare) {
	            $pubTable = new Model_DbTable_Pub();
	            $pubTable->getAdapter()->update('pub', array('idFoursquare' => $idFoursquare, 'validated' => true), 'id = ' . $idPub);
	        }
	    }
	    
	    $pubTable = new Model_DbTable_Pub();
	    
	    $pubService = new Service_Pub_Foursquare();
	    $pubs       = $pubTable->fetchAll($pubService->findPubByNotValid());
	    
	    $foursquarePubs = array();
	    foreach ($pubs as $pub) {
	        $foursquarePub = $this->foursquare->get('/venues/search', array(
	        	'query'      => $pub['name'], 
	        	'categoryId' => Aw_Service_Foursquare::CATEGORY_PUB . ',' . Aw_Service_Foursquare::CATEGORY_BAR, 
	        	'll'         => $pub['latitude'] . ',' . $pub['longitude']));
	        
	        $foursquarePubs[] = array(
	            'dunia'      => $pub,
	            'foursquare' => $foursquarePub->response->groups[0]->items);
	    }
	    
	    $this->view->pubs = $foursquarePubs;
	}
	
	public function tipsAction() {
	     
	    $pubTable   = new Model_DbTable_Pub();
	    $tipService = new Service_Tip();
	    $pubService = new Service_Pub();
	    
	    foreach ($pubTable->fetchAll($pubService->getPubs(array('validated' => true))) as $pub) {
	        $tips = $this->foursquare->get('/venues/' . $pub['idFoursquare'] . '/tips');
	        foreach ($tips->response->tips->items as $tip) {
                $tipService->saveTipFromFoursquareResult($pub['id'], $tip);
	        }
	    }
	     
	    exit;
	}
	
	public function validateTipsAction() {
	    $tipService = new Service_Tip();
	    
	    $this->view->pubs = $tipService->getNonValidatedTipsByPub();
	}
	
	public function moderateTipAction() {
	    $action = $this->_getParam('do');
	    $idTip  = $this->_getParam('idTip');
	    
	    if ($idTip && $action) {
	        $tipService = new Service_Tip();
            $tipService->moderateTip($idTip, $action);	        
	    }
	    
	    exit;
	}
	
	public function crawlAction() {
	    $user = $this->_getUser();
	    
        $latitude  = $this->_getParam('latitude', $user->getLat());
        $longitude = $this->_getParam('longitude', $user->getLong());
        
        $pubs = $this->foursquare->get('/venues/search', array(
            'radius'	 => 1000,
            'limit'	     => 50,
        	'categoryId' => Aw_Service_Foursquare::CATEGORY_PUB . ',' . Aw_Service_Foursquare::CATEGORY_BAR, 
        	'll'         => $latitude . ',' . $longitude));
        
        $db = Zend_Db_Table::getDefaultAdapter();
        
        foreach ($pubs->response->groups[0]->items as $pub) {
            try {
                $db->query("insert into discover (id, name, category, latitude, longitude, json) VALUES ('" . $pub->id . "', '" . $pub->name . "', '" . $pub->categories[0]->name . "', '" . $pub->location->lat . "', '" . $pub->location->lng . "', '" . json_encode($pub) . "')");
            } catch (Exception $e) {
                // Fuck it, it will be a duplicated ID exception
            }
        }
        
        $this->_forward('dirty');
	}
	
	public function dirtyAction() {
	    $user = $this->_getUser();
	     
	    $this->view->latitude  = $this->_getParam('latitude', $user->getLat());
	    $this->view->longitude = $this->_getParam('longitude', $user->getLong());
	    
	    $db        = Zend_Db_Table::getDefaultAdapter();
	    $pubs      = $db->fetchAll("select * from discover");
	    $pubsArray = array();
	    
	    foreach ($pubs as $pub) {
	        $pubsArray[] = array(
				'name' 		=> array($pub['name']),
				'lat' 		=> array($pub['latitude']),
				'lng' 		=> array($pub['longitude']),
				'type'		=> array('bar')
	        );
	    }
	    
	    $this->view->pubs = json_encode($pubsArray);
	}
}
