<?php
class MapController extends Model_Controller_Action {
	
	public function fetchAction() {
		
		$pubService = new Service_Pub();
		
		$user = $this->_getUser();
		
		$lat = $this->_getParam('lat', $user->getLat());
		$long = $this->_getParam('long', $user->getLong());
		
		$pubs = $pubService->findPubByLatLong($lat, $long);
		
		$array = array();
		foreach ($pubs as $pub) {
			$array[] = array(
				'name' 		=> array($pub['name']),
				'address' 	=> array((string) $pub->getAddress()),
				'lat' 		=> array($pub['latitude']),
				'lng' 		=> array($pub['longtitude']),
				'type' 		=> array('bar'),
			);
		}
		
		header('text/json');
		echo json_encode($array);
		exit;
	}
}