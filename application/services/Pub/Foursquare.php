<?php
class Service_Pub_Foursquare extends Service_Pub {
	
	private $_foursquare;
	
	public $latitude, $longitude;
	
	public function init() {
		$application = Zend_Registry::get('Zend_Application');
		$this->_foursquare = $application->getResource('foursquare');
	}
	
	public function findPubByValid() {
		return $this->getPubs(array('validated' => true));
	}
	
	/**
	 * Discover and save into discovery table.
	 */
	public function crawl() {
		$this->isNotEmpty(array('Latitude' => $this->latitude, 'Longitude' => $this->longitude));
		
		$pubs = $this->_foursquare->get('/venues/search', array(
				'radius'	 => 1000,
				'limit'	     => 50,
				'categoryId' => Aw_Service_Foursquare::CATEGORY_PUB . ',' . Aw_Service_Foursquare::CATEGORY_BAR,
				'll'         => $this->latitude . ',' . $this->longitude));
		
		
		$discoveryTable = new Model_DbTable_Discover();
		foreach ($pubs->response->groups[0]->items as $pub) {
			$isValidCategory = false;
			foreach ($pub->categories as $category) {
				if ($category->id == Aw_Service_Foursquare::CATEGORY_PUB || $category->id == Aw_Service_Foursquare::CATEGORY_BAR) {
					$isValidCategory = true;	
				}
			}
			
			if (true || $isValidCategory) {
					
					$data = array(
						'id' => $pub->id,
						'name' => $pub->name,
						'category' => $pub->categories[0]->name,
						'latitude' => $pub->location->lat,
						'longitude' => $pub->location->lng,
						'json' => json_encode($pub)
					);
					
					$row = $discoveryTable->createRow($data);
					if (!$row->isExists()) {
						$row->save();
					}
					
				
			}
			
		}
	}
	
	public function  findPubByNotValid() {
		return $this->getPubs(array('validated' => false));
	}
	
	public function updateTips() {
		$pubTable = new Model_DbTable_Pub();
		$tipTable = new Model_DbTable_Tip();
		
		$db = $tipTable->getAdapter();
		
		try {
			$db->beginTransaction();
			
			$pubs       = $pubTable->fetchAll($pubService->findPubByNotValid());
			
			$foursquarePubs = array();
			foreach ($pubs as $pub) {
				$tips = $this->foursquare->get('/venues/' . $pub['idFoursquare'] . '/tips');
			
				foreach ($tips->response->tips->items as $tip) {
					$tipRow = $tipTable->getRow(array(
							'idPub'       => $pub['id'],
							'text'        => $tip->text,
							'data'        => json_encode($tip),
							'dateUpdated' => date("Y-m-d H:i:s"),
					)
					);
					$tipRow->save();
				}
			}
			
			
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw  $e;
		}
		
	}
}