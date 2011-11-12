<?php
class MapController extends Zend_Controller_Action {
	public function fetchAction() {
		$simpleXml = simplexml_load_file(APPLICATION_ROOT . '/data/fetch.xml');
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