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
	
	private function _crawl($pubs) {
		$discoveryTable = new Model_DbTable_Discover();
		
		if (!isset($pubs->response->venues) || !is_array($pubs->response->venues)) {
            var_dump($pubs->response);
            return;
		}
		
		foreach ($pubs->response->venues as $pub) {
		
			$isValidCategory = false;
			foreach ($pub->categories as $category) {
				if (in_array($category->id, Aw_Service_Foursquare::$allowedCategories)) {
					$isValidCategory = true;
				}
			}
			
			if (true || $isValidCategory) {
				
				$data = array(
				    'id'       => $pub->id,
				    'name'     => $pub->name,
				    'category' => $pub->categories[0]->name,
				    'latitude' => $pub->location->lat,
				    'longitude' => $pub->location->lng,
				    'json'     => json_encode($pub)
				);
				
				$row = $discoveryTable->createRow($data);
				if (!$row->isExists()) {
					$row->save();
					echo 'S';
				}
			}
				
		}
	}
	
	public function crawlLinear() {
		$this->isNotEmpty(array('Latitude' => $this->latitude, 'Longitude' => $this->longitude));
		$pubs = $this->_foursquare->getLin('/venues/search', array(
				'radius'	 => 100,
				'v'			 => 20111212,
				'limit'	     => 50,
                'intent'     => 'browse',
                'categoryId' => implode(',', Aw_Service_Foursquare::$allowedCategories),
                'll'         => $this->latitude . ',' . $this->longitude));
		$this->_crawl($pubs);
	}

    /**
     * Discover and save into discovery table.
     */
    public function crawl() {
		$this->isNotEmpty(array('Latitude' => $this->latitude, 'Longitude' => $this->longitude));
		$pubs = $this->_foursquare->get('/venues/search', array(
                'radius'	 => 100,
                'v'			 => 20111212,
                'intent'     => 'browse',
				'limit'	     => 50,
				'categoryId' => implode(',', Aw_Service_Foursquare::$allowedCategories),
				'll'         => $this->latitude . ',' . $this->longitude));
		$this->_crawl($pubs);
		
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
			
			$pubs       = $pubTable->fetchAll($this->findPubByNotValid());
			
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