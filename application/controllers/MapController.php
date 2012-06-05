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
		
		$pubs = $pubService->findPromo($lat, $long, null, null, null, null, true);
		
		$this->_generateResponse($pubs);
	}
	
	public function fetchBoundAction() {
		$user = $this->_getUser();
		
		$lat = ($user->getLat() ?: self::LAT);
		$long = ($user->getLong() ?: self::LONG);
		
		$lat  = $this->_getParam('lat', $lat);
		$long = $this->_getParam('long', $long);
		
		$ne = $this->_getParam('ne');
		$sw = $this->_getParam('sw');

        $zoom = $this->_getParam('zoom');
		
		list($nelat, $nelong) = explode(",", $ne);
		list($swlat, $swlong) = explode(",", $sw);
		
		$pubService = new Service_Pub();
		
		
		$bound = new Model_Location_Bound();
		$bound->nelat  = $nelat;
		$bound->nelng  = $nelong;
		$bound->swlat  = $swlat;
		$bound->swlng  = $swlong;

        if ($zoom >= 17) {
		    $pubs = $pubService->findPromo($lat, $long, null, null, null, $bound, true);
        } else {
            $pubs = $pubService->findPromo($lat, $long, null, null, null, $bound);
        }
		$this->_generateResponse($pubs);
		
	}
	
	public function searchAction() {
	    // Refactor this. The service should return a collection and not a select
	    $pubTable = new Model_DbTable_Pub();
	    
	    $pubService = new Service_Pub();
	    $pubs       = $pubTable->fetchAll($pubService->searchPub($this->_getParam('q')));
	    
	    $this->_generateResponse($pubs);
	}
	
	private function _generateResponse($pubs) {
	    $array = array();
	    foreach ($pubs as $pub) {
	        $array[] = array(
	            'id'		=> array($pub['id']),
	    	    'name' 		=> array($pub['name']),
	    	    'address' 	=> array($pub['address']),
	    	    'lat' 		=> array($pub['latitude']),
	    	    'lng' 		=> array($pub['longitude']),
	    	    'type' 		=> array('bar'),
	            'itsOn'		=> array($pub['itsOn']),
                'promos'    => $pub['promos'],
	            'url'		=> array($pub['url']),
	        );
	    }
	    
	    header('text/json');
	    echo json_encode($array);
	    exit;
	}
}