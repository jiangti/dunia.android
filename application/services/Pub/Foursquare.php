<?php
class Service_Pub_Foursquare extends Service_Pub {
	public function findPubByValid() {
		return $this->_getPubs(array('validated' => true));
	}
	
	public function  findPubByNotValid() {
		return $this->_getPubs(array('validated' => false));
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