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
    
	public function validateAction() {
	    if ($this->_request->isPost()) {
	        foreach ($this->_getParam('pub') as $idPub => $idFoursquare) {
	            $pubTable = new Model_DbTable_Pub();
	            $pubTable->getAdapter()->update('pub', array('idFoursquare' => $idFoursquare, 'validated' => true), 'id = ' . $idPub);
	        }
	    }
	    
	    // Refactor this. The service should return a collection and not a select
	    $pubTable = new Model_DbTable_Pub();
	    
	    $pubService = new Service_Pub();
	    $pubs       = $pubTable->fetchAll($pubService->getPubs(array('validated' => false)));
	    
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
	
}