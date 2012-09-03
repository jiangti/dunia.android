<?php
class Model_DbTable_UserLikesPub extends Aw_Table_Relation_TableAbstract {
	protected $_name     = 'userLikesPub';
	protected $_primary  = array('idUser', 'idPub');

	protected $_referenceMap = array(
		'user' => array(
			'columns'       => 'idUser',
			'refTableClass' => 'Model_DbTable_User',
			'refColumns'    => 'id'
		),
		'pub' => array(
			'columns'       => 'idPub',
			'refTableClass' => 'Model_DbTable_Pub',
			'refColumns'    => 'id'
		)
	);
}