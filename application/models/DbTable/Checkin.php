<?php
class Model_DbTable_PubHasPromo extends Aw_Table_Relation_TableAbstract {
	protected $_name = 'pubHasPromo';
	protected $_primary = array('idPub', 'idPromo');
	
	protected $_referenceMap = array(
		'pub' => array(
			'columns'       => 'idPub',
			'refTableClass' => 'Model_DbTable_Pub',
			'refColumns'    => 'id'
		),
		'promo' => array(
			'columns'       => 'idPromo',
			'refTableClass' => 'Model_DbTable_Promo',
			'refColumns'    => 'id'
		)
	);
}