<?php
class Service_Pub {
	public function savePub(Model_Pub $pub) {
		$pubTable = new Model_DbTable_Pub();
		
		if (!$pubRow = $pubTable->findByName($pub->name)) {
			$pubRow = $pubTable->createRow($pub->getArray());
		}
	}
}