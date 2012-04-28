<?php
class Model_DbTable_Flag extends Aw_Table_Relation_TableAbstract {
	protected $_name = 'flag';

	protected $_referenceMap = array(
		'pub' => array(
			'columns'       => 'idPub',
			'refTableClass' => 'Model_DbTable_Pub',
			'refColumns'    => 'id'
		),
		'promo' => array(
			'columns'       => 'idUser',
			'refTableClass' => 'Model_DbTable_User',
			'refColumns'    => 'id'
		)
	);
}