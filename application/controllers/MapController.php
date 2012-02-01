<?php
class MapController extends Zend_Controller_Action {
	
	const DEFAULT_LATITUDE  = -33.8757;
	const DEFAULT_LONGITUDE = 151.206;
	
	public function fetchAction() {
		
		$pubService = new Service_Pub();
		
		$pubs = $pubService->findPubByLatLong(self::DEFAULT_LATITUDE, self::DEFAULT_LONGITUDE);
		$simpleXml = simplexml_load_file(APPLICATION_ROOT . '/data/fetch.xml');
		//$pubs->loadAddress();
		
		$array = array();
		
		foreach ($simpleXml as $node) {
			$array[] = array(
					                               'name' => $node['name'],
					                               'address' => $node['address'],
					                               'lat' => $node['lat'],
					                               'lng' => $node['lng'],
					                               'type' => $node['type'],
					);
		}
		
		
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