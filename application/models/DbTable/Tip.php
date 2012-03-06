<?php
class Model_DbTable_Tip extends Model_DbTable_TableAbstract {
	protected $_name = 'tip';
	protected $_rowsetClass = 'Model_DbTable_Rowset_Tip';
	protected $_rowClass = 'Model_DbTable_Row_Tip';

	protected $_referenceMap    = array(
	    'pub' => array(
	        'columns'           => 'idPub',
	        'refTableClass'     => 'Model_DbTable_Pub',
	        'refColumns'        => 'id'
	    ),
	);
	
}