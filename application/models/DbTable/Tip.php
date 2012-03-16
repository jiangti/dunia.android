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
	
	const TIP_NON_MODERATED = 0;
	const TIP_APPROVED      = 1;
	const TIP_REJECTED      = 2;
}