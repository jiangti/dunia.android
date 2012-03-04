<?php
class Model_LiquorType extends Aw_Model_ModelAbstract {
    public $id;
	public $name;
	
	
	
	public static function getAllByName() {
	    $liquorTable = new Model_DbTable_LiquorType();
	    $select = $liquorTable->select()->order('name');
	    return $liquorTable->getAdapter()->fetchPairs($select);
	}
	
	public static function parse($str) {
		$pairs = self::getAllByName();
		foreach ($pairs as $id => $name) {
			if (stripos($str, $name) !== false) {
				return Model_DbTable_LiquorType::retrieveById($id);
			}
		}
		
		$localBeer = array('Schooner', 'Middies', 'Blondes');
		
		foreach ($localBeer as $beer) {
			if (stripos($str, $beer) !== false) {
				return Model_DbTable_LiquorType::retrieveById(array_keys($pairs, 'Beer'));
			}
		}
		
		$importedBeer = array('Heineken', 'Corona', 'Boags', 'Hoegaarden');
		
		foreach ($importedBeer as $beer) {
			if (stripos($str, $beer) !== false) {
				return Model_DbTable_LiquorType::retrieveById(array_keys($pairs, 'Imported Beer'));
			}
		}
		
	}
}