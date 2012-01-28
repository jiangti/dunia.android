<?php
class Model_LiquorType extends Aw_Model_ModelAbstract {
    public $id;
	public $name;
	
	public static function getAllByName() {
	    $liquorTable = new Model_DbTable_LiquorType();
	    $select = $liquorTable->select()->order('name');
	    return $liquorTable->getAdapter()->fetchPairs($select);
	}
}