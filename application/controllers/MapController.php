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
		
		$params = $this->_getAllParams();
		$params['ne'] = sprintf('%s,%s', $lat + 0.005, $long + 0.005);
        $params['sw'] = sprintf('%s,%s', $lat - 0.005, $long - 0.005);
		
		$this->_forward('fetch-bound', null, null, $params);
	}
	
	public function fetchBoundAction() {
		$user = $this->_getUser();
		
		$lat  = $this->_getParam('lat', $user->getLat());
		$long = $this->_getParam('long', $user->getLong());
        $zoom = $this->_getParam('zoom');

        setcookie('lat', $lat, time() + 86400, '/');
        setcookie('long', $long, time() + 86400, '/');
        setcookie('zoom', $zoom, time() + 86400, '/');

        $ne = $this->_getParam('ne');
        $sw = $this->_getParam('sw');

        list($nelat, $nelong) = explode(",", $ne);
		list($swlat, $swlong) = explode(",", $sw);
		
		$pubService = new Service_Pub();
		
		
		$bound = new Model_Location_Bound();
		$bound->nelat  = $nelat;
		$bound->nelng  = $nelong;
		$bound->swlat  = $swlat;
		$bound->swlng  = $swlong;

		$options['day'] = $this->_getParam('day', null);
        if (Zend_Registry::get('device')->getType() == 'mobile') {
            $options['todayOnly'] = true;
        }
		
        $pubs = $pubService->findPromo($lat, $long, null, null, null, $bound, false, $options);
        
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
                'icon'      => array($pub['icon']),
	            'idPubType' => $pub['idPubType'],
	            'dealise'   => $this->view->partial('partials/promo-table.phtml', array('pub' => Model_DbTable_Pub::retrieveById($pub['id'])))
	        );
	    }
	    
	    header('text/json');
	    echo json_encode($array);
	    exit;
	}
}
