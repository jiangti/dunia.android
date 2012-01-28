<?php
class Model_DbTable_Pub extends Model_DbTable_TableAbstract {
	protected $_name = 'pub';
	protected $_rowClass = 'Model_DbTable_Row_Pub';

	protected $_referenceMap    = array(
	    'address' => array(
	        'columns'           => 'idAddress',
	        'refTableClass'     => 'Model_DbTable_Address',
	        'refColumns'        => 'id'
	    ),
	);


	public function findByName($name) {
		$select = $this->select();
		$select->where('name = ?', $name);
		return $this->fetchRow($select);
	}
}