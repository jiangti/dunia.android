<?php
class Model_DbTable_Pub extends Model_DbTable_TableAbstract {
	protected $_name = 'pub';
	
	public function findByName($name) {
		$select = $this->select();
		$select->where('name = ?', $name);
		return $this->fetchRow($select);
	}
}