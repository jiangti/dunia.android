<?php
class MapController extends Zend_Controller_Action {
	public function fetchAction() {
		$simpleXml = simplexml_load_file(APPLICATION_ROOT . '/data/fetch.xml');
		
		//SELECT address, name, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20		
		
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
		
		header('text/json');
		echo json_encode($array); 
		exit;
	}
}