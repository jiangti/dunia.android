<?php
class MapController extends Model_Controller_Action {
	
	/**
	 * Defaults to Sydney
	 */
	const LONG = 	-33.8654;
	const LAT = 	151.2073;
	
	public function fetchAction() {
		
		$pubService = new Service_Pub();
		
		$user = $this->_getUser();
		
		$lat = ($user->getLat() ?: self::LAT);
		$long = ($user->getLong() ?: self::LONG);
		
		$lat  = $this->_getParam('lat', $lat);
		$long = $this->_getParam('long', $long);
		
		$pubs = $pubService->findPromo($lat, $long);
		
		$this->_generateResponse($pubs);
	}
	
	public function searchAction() {
	    // Refactor this. The service should return a collection and not a select
	    $pubTable = new Model_DbTable_Pub();
	    
	    $pubService = new Service_Pub();
	    $pubs       = $pubTable->fetchAll($pubService->searchPub($this->_getParam('q')));
	    
	    $this->_generateResponse($pubs);
	}
	
	private function _generateResponse(Zend_Db_Table_Rowset_Abstract $pubs) {
	    $array = array();
	    foreach ($pubs as $pub) {
	        $array[] = array(
	                    'id'		 => array($pub['id']),
	    				'name' 		 => array($pub['name']),
	    				'address' 	 => array((string) $pub->getAddress()),
	    				'lat' 		 => array($pub['latitude']),
	    				'lng' 		 => array($pub['longitude']),
	    				'type' 		 => array('bar'),
	        			'itsOn'		 => array($pub['itsOn']),
                        'liquorType' => array($pub['liquorType'])
	        );
	    }
	    
	    header('text/json');
	    echo json_encode($array);
	    exit;
	}
}