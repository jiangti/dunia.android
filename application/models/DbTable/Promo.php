<?php
class Model_DbTable_Promo extends Model_DbTable_TableAbstract {
	protected $_name = 'promo';
	protected $_rowClass = 'Model_DbTable_Row_Promo';
	
	protected $_referenceMap    = array(
	    'pub' => array(
            'columns'           => 'idPub',
            'refTableClass'     => 'Model_DbTable_Pub',
            'refColumns'        => 'id'
        ),
	);
	
}