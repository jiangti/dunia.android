<?php
class Service_LiquorSize {
	public static function getAllByName() {
	    $liquorTable = new Model_DbTable_LiquorSize();
	    $select = $liquorTable->select()->order('name');
	    return $liquorTable->getAdapter()->fetchPairs($select);
	}
	
	public static function parse($str) {
		$pairs = self::getAllByName();
		foreach ($pairs as $id => $name) {
			if (stripos($str, $name) !== false) {
				return Model_DbTable_LiquorSize::retrieveById($id);
			}
		}
	}
}