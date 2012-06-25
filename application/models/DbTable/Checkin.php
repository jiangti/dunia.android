<?php
class Model_DbTable_Checkin extends Aw_Table_Relation_TableAbstract {
	protected $_name = 'checkin';

	protected $_referenceMap = array(
		'pub' => array(
			'columns'       => 'idPub',
			'refTableClass' => 'Model_DbTable_Pub',
			'refColumns'    => 'id'
		),
		'user' => array(
			'columns'       => 'idUser',
			'refTableClass' => 'Model_DbTable_User',
			'refColumns'    => 'id'
		)
	);
}